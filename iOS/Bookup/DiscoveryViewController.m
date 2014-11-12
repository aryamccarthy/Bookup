//
//  DiscoveryViewController.m
//  Bookup
//
//  Created by Arya McCarthy on 11/9/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import "DiscoveryViewController.h"
#import "Book.h"

@interface DiscoveryViewController ()
@property (weak, nonatomic) IBOutlet UILabel *titleLabel;
@property (weak, nonatomic) IBOutlet UILabel *authorLabel;
@property (weak, nonatomic) IBOutlet UITextView *descriptionTextView;
@property (strong, nonatomic) Book *book;
@property (weak, nonatomic) IBOutlet UIBarButtonItem *popoverButton;
@property (strong, nonatomic) IBOutlet UIImageView *bookCover;
@end

@implementation DiscoveryViewController

- (UIImageView *)bookCover {
  if (!_bookCover)
    _bookCover = [[UIImageView alloc] init];
  return _bookCover;
}

- (Book *)book {
  if (!_book) _book = [[Book alloc] init];
  return _book;
}

- (void)viewDidLoad {
    [super viewDidLoad];
  // Do any additional setup after loading the view.
  [self getABook];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (IBAction)nextBook:(id)sender {
  [self getABook];
}

- (void)updateUI {
  //NSLog(@"%@", self.book);
  self.titleLabel.text = self.book.myTitle;
  self.authorLabel.text = self.book.myAuthorsAsString;
  self.descriptionTextView.text = self.book.myDescription;
  self.descriptionTextView.textAlignment = NSTextAlignmentJustified;
  [self resetImage];
}
- (void)resetImage
{
    [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:YES];
    NSURL *imageURL = self.book.myImageURL;    // grab the URL before we start (then check it below)
    dispatch_queue_t imageFetchQ = dispatch_queue_create("image fetcher", NULL);
    dispatch_async(imageFetchQ, ^{
      NSLog(@"We /are/ executing this.");
      NSData *imageData = [[NSData alloc] initWithContentsOfURL:self.book.myImageURL];  // could take a while
      // UIImage is one of the few UIKit objects which is thread-safe, so we can do this here
      UIImage *image = [[UIImage alloc] initWithData:imageData];
      // check to make sure we are even still interested in this image (might have touched away)
      if (self.book.myImageURL == imageURL) {
        NSLog(@"And this part?");
        // dispatch back to main queue to do UIKit work
        dispatch_async(dispatch_get_main_queue(), ^{
          if (image) {
            NSMutableParagraphStyle *paragrapStyle = NSMutableParagraphStyle.new;
            paragrapStyle.alignment                = NSTextAlignmentCenter;
            NSLog(@"GOT HERE.");
            NSDictionary *textAttributes = @{NSParagraphStyleAttributeName:paragrapStyle};
            NSString *descr = self.book.myDescription; // Since we get malformed JSON ALL THE TIME.
            if (!descr)
              descr = @"";
            NSMutableAttributedString *attributedString = [[NSMutableAttributedString alloc] initWithString:[@" \n" stringByAppendingString:descr] attributes:textAttributes];
            NSLog(@"DID THIS");
            NSTextAttachment *textAttachment = [[NSTextAttachment alloc] init];
            textAttachment.image = image;
            NSAttributedString *attrStringWithImage = [NSAttributedString attributedStringWithAttachment:textAttachment];
            [attributedString replaceCharactersInRange:NSMakeRange(0, 1) withAttributedString:attrStringWithImage];
            NSMutableParagraphStyle *paragraphStyle = [[NSMutableParagraphStyle alloc] init] ;

            [paragraphStyle setAlignment:NSTextAlignmentCenter];            // centers image horizontally

            [paragraphStyle setParagraphSpacing:10]; // MAGIC NUMBER

            [attributedString addAttribute:NSParagraphStyleAttributeName value:paragraphStyle range:NSMakeRange(0, 1)];
            //[attributedString setAttributes:textAttributes range:NSMakeRange(0, [attributedString length])]; // Doesn't actually work. Image will not display.
            [self.descriptionTextView setAttributedText:attributedString];
            [self.descriptionTextView setScrollEnabled:YES];
//            NSLog(@"How about this? %@", imageURL);
//            self.bookCover.image = image;
//            self.bookCover.frame = CGRectMake(0, 0, image.size.width, image.size.height);
//            self.bookCover.contentMode = UIViewContentModeScaleAspectFit;
//            [self.descriptionTextView addSubview:self.bookCover];
//            CGRect exclusionArea = self.bookCover.frame;
//            NSLog(@"%@", NSStringFromCGRect(exclusionArea));
//            UIBezierPath *path = [UIBezierPath bezierPathWithRect:exclusionArea];
//            self.descriptionTextView.textContainer.exclusionPaths = @[path];
          }
          [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
        });
      }
    });
}

- (void) getABook {
  NSMutableURLRequest *request =
  [NSMutableURLRequest requestWithURL:[NSURL URLWithString:@"http://ec2-54-187-70-205.us-west-2.compute.amazonaws.com/API/index.php/getRandomBook"]
                          cachePolicy:NSURLRequestReloadIgnoringLocalAndRemoteCacheData
                      timeoutInterval:10];
  [request setHTTPMethod:@"GET"];
  [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:YES];
  NSURLConnection *conn = [[NSURLConnection alloc] initWithRequest:request delegate:self];
}

#pragma mark NSURLConnection Delegate Methods

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response {
  // A response has been received, this is where we initialize the instance var you created
  // so that we can append data to it in the didReceiveData method
  // Furthermore, this method is called each time there is a redirect so reinitializing it
  // also serves to clear it
  NSLog(@"%@", @"Did receive response.");
  _responseData = [[NSMutableData alloc] init];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
  // Append the new data to the instance variable you declared
  NSLog(@"%@", @"Did receive data.");
  [_responseData appendData:data];
}

- (NSCachedURLResponse *)connection:(NSURLConnection *)connection
                  willCacheResponse:(NSCachedURLResponse*)cachedResponse {
  // Return nil to indicate not necessary to store a cached response for this connection
  return nil;
}

- (IBAction)appInfo:(id)sender {
  UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"Bookup for iPhone" message:@"\nCopyright © 2014.\n\nKatherine Habeck\nArya McCarthy" preferredStyle:UIAlertControllerStyleAlert];
  UIAlertAction *cancel = [UIAlertAction actionWithTitle:@"Thanks, guys!" style:UIAlertActionStyleCancel handler:nil];
  [alert addAction:cancel];
  [self presentViewController:alert animated:YES completion:nil];
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
  // The request is complete and data has been received
  // You can parse the stuff in your instance variable now
  //NSLog(@"%@", @"Did finish loading.");
  [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
  NSError *parseError;
  NSDictionary *resultsFromJSON = [NSJSONSerialization JSONObjectWithData:_responseData options:0 error:&parseError];
  //NSLog(@"%@", json);

  NSArray *bookArray = resultsFromJSON[@"Books"];
  NSString *this_book = bookArray[0];
  //NSLog(@"%@", this_book);

  NSData *thisBookData = [this_book dataUsingEncoding:NSUTF8StringEncoding];
  NSError *parseError2;
  NSDictionary *json2 = [NSJSONSerialization JSONObjectWithData:thisBookData options:0 error:&parseError2];
  NSDictionary *volumeInfo = json2[@"items"][0][@"volumeInfo"];
  self.book.myTitle = volumeInfo[@"title"];
  self.book.myAuthors = volumeInfo[@"authors"];
  //NSString *authorsString = [authors componentsJoinedByString:@", "];
  self.book.myDescription = volumeInfo[@"description"];
  self.book.myImageURL = [NSURL URLWithString:volumeInfo[@"imageLinks"][@"thumbnail"]];
  [self updateUI];
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
  // The request has failed for some reason!
  // Check the error var
  //NSLog(@"%@", @"Did fail with error.");
  NSLog(@"%@", error);
  [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
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
