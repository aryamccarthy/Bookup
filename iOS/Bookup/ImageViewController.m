//
//  ImageViewController.m
//  Shutterbug
//
//  Created by CS193p Instructor.
//  Copyright (c) 2013 Stanford University. All rights reserved.
//

#import "ImageViewController.h"
#import "CentereedContentScrollView.h"
//#import "AttributedStringViewController.h"

@interface ImageViewController () <UIScrollViewDelegate>
@property (weak, nonatomic) IBOutlet UIScrollView *scrollView;
@property (strong, nonatomic) UIImageView *imageView;
//@property (weak, nonatomic) IBOutlet UIBarButtonItem *titleBarButtonItem;
@property (strong, nonatomic) UIPopoverController *urlPopover;
@property (weak, nonatomic) IBOutlet UIActivityIndicatorView *spinner;
@end

@implementation ImageViewController

- (BOOL)shouldPerformSegueWithIdentifier:(NSString *)identifier sender:(id)sender
{
    if ([identifier isEqualToString:@"Show URL"]) {
        return (self.imageURL && !self.urlPopover.popoverVisible) ? YES : NO;
    } else {
        return [super shouldPerformSegueWithIdentifier:identifier sender:sender];
    }
}

- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
/*    if ([segue.identifier isEqualToString:@"Show URL"]) {
        if ([segue.destinationViewController isKindOfClass:[AttributedStringViewController class]]) {
            AttributedStringViewController *asc = (AttributedStringViewController *)segue.destinationViewController;
            asc.text = [[NSAttributedString alloc] initWithString:[self.imageURL description]];
            if ([segue isKindOfClass:[UIStoryboardPopoverSegue class]]) {
                self.urlPopover = ((UIStoryboardPopoverSegue *)segue).popoverController;
            }
        }
    }*/
}

- (void)setTitle:(NSString *)title
{
    super.title = title;
    //self.titleBarButtonItem.title = title;
}

- (void)setImageURL:(NSURL *)imageURL
{
    _imageURL = imageURL;
    [self resetImage];
}

- (void)resetImage
{
    if (self.scrollView) {
        self.scrollView.contentSize = CGSizeZero;
        self.imageView.image = nil;
        
        [self.spinner startAnimating];      // if self.spinner is nil, does nothing
      [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:YES];
        NSURL *imageURL = self.imageURL;    // grab the URL before we start (then check it below)
        dispatch_queue_t imageFetchQ = dispatch_queue_create("image fetcher", NULL);
        dispatch_async(imageFetchQ, ^{
            //[NSThread sleepForTimeInterval:2.0]; // simulate network latency for testing
            // really we should probably keep a count of threads claiming network activity
            [UIApplication sharedApplication].networkActivityIndicatorVisible = YES; // bad
            NSData *imageData = [[NSData alloc] initWithContentsOfURL:self.imageURL];  // could take a while
            [UIApplication sharedApplication].networkActivityIndicatorVisible = NO; // bad
            // UIImage is one of the few UIKit objects which is thread-safe, so we can do this here
            UIImage *image = [[UIImage alloc] initWithData:imageData];
            // check to make sure we are even still interested in this image (might have touched away)
            if (self.imageURL == imageURL) {
                // dispatch back to main queue to do UIKit work
                dispatch_async(dispatch_get_main_queue(), ^{
                    if (image) {
                        self.scrollView.zoomScale = 1.0;
                        self.scrollView.contentSize = image.size;
                        self.imageView.image = image;
                      self.imageView.frame = CGRectMake(0, 0, image.size.width, image.size.height);
                      [self.scrollView layoutSubviews];
                      NSLog(@"%@", NSStringFromCGRect(self.imageView.frame));
                    }
                    [self.spinner stopAnimating];  // spinner should have hidesWhenStopped set
                  [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
                });
            }
        });
    }
}

- (UIImageView *)imageView
{
    if (!_imageView) _imageView = [[UIImageView alloc] initWithFrame:CGRectZero];
    return _imageView;
}

- (UIView *)viewForZoomingInScrollView:(UIScrollView *)scrollView
{
    return self.imageView;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
  if ([self.scrollView isKindOfClass:[CentereedContentScrollView class]]) {
    [(CentereedContentScrollView *)self.scrollView setImageView:self.imageView];
  }
    [self.scrollView addSubview:self.imageView];
    self.scrollView.minimumZoomScale = 1.0;
    self.scrollView.maximumZoomScale = 2.0;
    self.scrollView.delegate = self;
  [self.scrollView setShowsHorizontalScrollIndicator:NO];
  [self.scrollView setShowsVerticalScrollIndicator:NO];

    [self resetImage];
    //self.titleBarButtonItem.title = self.title;
}

@end