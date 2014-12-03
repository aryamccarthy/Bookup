//
//  ReadingListEntryViewController.m
//  Bookup
//
//  Created by Arya McCarthy on 11/13/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import "ReadingListEntryViewController.h"

@interface ReadingListEntryViewController ()
@property (weak, nonatomic) IBOutlet UITextView *descriptionTextView;

@end

@implementation ReadingListEntryViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    [self resetData];
    UIImageView *bgImageView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"background2"]];
    bgImageView.frame = self.view.bounds;
    bgImageView.contentMode = UIViewContentModeScaleAspectFill;
    bgImageView.alpha = 0.05;
    [self.view addSubview:bgImageView];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)resetData
{
  NSURL *imageURL = self.book.myImageURL;
  dispatch_queue_t imageFetchQ = dispatch_queue_create("image fetcher", NULL);
  dispatch_async(imageFetchQ, ^{
    NSError *error;
    [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:YES];
    NSData *imageData = [[NSData alloc] initWithContentsOfURL:self.book.myImageURL options:0 error:&error];
    [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
    UIImage *image = [[UIImage  alloc] initWithData:imageData];
    if (self.book.myImageURL == imageURL) {
      dispatch_async(dispatch_get_main_queue(), ^{
        if (image) {
          NSMutableParagraphStyle *style = [[NSMutableParagraphStyle alloc] init];
          style.alignment = NSTextAlignmentJustified;
          style.hyphenationFactor = 1.0f;
          UIFont *font = [UIFont preferredFontForTextStyle:UIFontTextStyleCaption1];
          NSDictionary *textAttributes = @{NSParagraphStyleAttributeName:style, NSFontAttributeName: font};
          NSString *descr = self.book.myDescription;
          if (!descr)
            descr = @"";
          NSMutableAttributedString *attributedString = [[NSMutableAttributedString alloc] initWithString:[@" \n" stringByAppendingString:descr] attributes:textAttributes];
          NSTextAttachment *textAttachment = [[NSTextAttachment alloc] init];
          textAttachment.image = image;
          NSAttributedString *attrStringWithImage = [NSAttributedString attributedStringWithAttachment:textAttachment];
          [attributedString replaceCharactersInRange:NSMakeRange(0, 1) withAttributedString:attrStringWithImage];
          NSMutableParagraphStyle *imageStyle = [[NSMutableParagraphStyle alloc] init];
          imageStyle.alignment = NSTextAlignmentCenter;
          imageStyle.paragraphSpacing = 10;
          [attributedString addAttribute:NSParagraphStyleAttributeName value:imageStyle range:NSMakeRange(0, 1)];
          self.descriptionTextView.attributedText = attributedString;
        }
      });
    }


  });
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
