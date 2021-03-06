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
@property (weak, nonatomic) IBOutlet UIBarButtonItem *addButton;
@property (weak, nonatomic) IBOutlet UIToolbar *toolbar;
@property (strong, nonatomic) IBOutlet UILongPressGestureRecognizer *longPressRecognizer;
@property (nonatomic) BOOL acceptsLongPress; // workaround because reasons.
@end

@implementation DiscoveryViewController

typedef NS_ENUM(NSInteger, BookupPreferenceValue) {
  BookupPreferenceValueLike = 1,
  BookupPreferenceValueDislike = -1
};

- (void)viewDidLoad {
    [super viewDidLoad];
  //[self.tabBarController.tabBar setTintColor:[UIColor redColor]];
    self.acceptsLongPress = YES;
    [self getABook];
    [self.descriptionTextView setContentInset:UIEdgeInsetsMake(0, 0, self.toolbar.frame.size.height, 0)];
    UIImageView *bgImageView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"background2"]];
    bgImageView.frame = self.view.bounds;
    bgImageView.contentMode = UIViewContentModeScaleAspectFill;
    bgImageView.alpha = 0.15;
    [self.view addSubview:bgImageView];
    [self.view sendSubviewToBack:bgImageView];
}

#pragma mark Property functions

- (UIImageView *)bookCover {
  if (!_bookCover)
    _bookCover = [[UIImageView alloc] init];
  return _bookCover;
}

- (Book *)book {
  if (!_book) _book = [[Book alloc] init];
  return _book;
}

- (IBAction)logout:(id)sender {
  UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"Logout" message:@"Are you sure you want to log out of Bookup?" preferredStyle:UIAlertControllerStyleAlert];
  UIAlertAction *cancel = [UIAlertAction actionWithTitle:@"No" style:UIAlertActionStyleCancel handler:nil];
  [alert addAction:cancel];
  UIAlertAction *logout = [UIAlertAction actionWithTitle:@"Yes" style:UIAlertActionStyleDestructive handler:^(UIAlertAction *action){
    NSUserDefaults * defs = [NSUserDefaults standardUserDefaults];
    NSDictionary * dict = [defs dictionaryRepresentation];
    for (id key in dict) {
      [defs removeObjectForKey:key];
    }
    [defs synchronize];
    [self dismissViewControllerAnimated:YES completion:nil];
  }];
  [alert addAction:logout];
  [self presentViewController:alert animated:YES completion:nil];
}


- (IBAction)nextBook:(id)sender {
  [self getABook];
}

#pragma mark Updating the UI

- (void) enableAllButtons {
  self.addButton.enabled = YES;
  self.longPressRecognizer.enabled = YES;
  self.acceptsLongPress = YES;
}

- (void)updateUI
{
  self.titleLabel.text = self.book.myTitle;
  self.authorLabel.textColor = [UIColor blackColor];
  self.authorLabel.text = self.book.myAuthorsAsString;
  self.descriptionTextView.text = self.book.myDescription;
  self.descriptionTextView.textAlignment = NSTextAlignmentJustified;
  [self enableAllButtons];
  [self resetImage];
}
- (void)resetImage
{
    [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:YES];
    NSURL *imageURL = self.book.myImageURL;    // grab the URL before we start (then check it below)
    dispatch_queue_t imageFetchQ = dispatch_queue_create("image fetcher", NULL);
    dispatch_async(imageFetchQ, ^{
      NSData *imageData = [[NSData alloc] initWithContentsOfURL:self.book.myImageURL];  // could take a while
      // UIImage is one of the few UIKit objects which is thread-safe, so we can do this here
      UIImage *image = [[UIImage alloc] initWithData:imageData];
      // check to make sure we are even still interested in this image (might have touched away)
      if (self.book.myImageURL == imageURL) {
        // dispatch back to main queue to do UIKit work
        dispatch_async(dispatch_get_main_queue(), ^{
          if (image) {
            NSMutableParagraphStyle *paragrapStyle = NSMutableParagraphStyle.new;
            paragrapStyle.alignment                = NSTextAlignmentJustified;
            paragrapStyle.hyphenationFactor = 1.0f;
            NSDictionary *textAttributes = @{NSParagraphStyleAttributeName:paragrapStyle, NSFontAttributeName: [UIFont preferredFontForTextStyle:UIFontTextStyleCaption1]};
            NSString *descr = self.book.myDescription; // Since we get malformed JSON ALL THE TIME.
            if (!descr)
              descr = @"";
            NSMutableAttributedString *attributedString = [[NSMutableAttributedString alloc] initWithString:[@" \n" stringByAppendingString:descr] attributes:textAttributes];
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
          }
          [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
        });
      }
    });
}

- (void) getABook {
  NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
  NSString *userEmail = [defaults objectForKey:@"userEmail"];
  NSMutableURLRequest *request =
  [NSMutableURLRequest requestWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"http://54.187.70.205/API/index.php/getRecommendedBook/%@", userEmail]]
                          cachePolicy:NSURLRequestReloadIgnoringLocalAndRemoteCacheData
                      timeoutInterval:10];
  [request setHTTPMethod:@"GET"];
  [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:YES];
  NSURLConnection *conn = [[NSURLConnection alloc] initWithRequest:request delegate:self];
}

#pragma mark Adding to Reading List

- (void)preventReAddingToList {
  self.addButton.enabled = NO;
  self.longPressRecognizer.enabled = NO; //Cancels gesture
  self.acceptsLongPress = NO;
}

- (IBAction)holdForDislike:(UILongPressGestureRecognizer *)sender {
  if (sender.state == UIGestureRecognizerStateBegan && self.acceptsLongPress) {
    NSLog(@"Long press occurred!");
    [self preventReAddingToList];
    [self showAddFeedback];
  }
}

- (IBAction)addCurrentToReadingList:(UIBarButtonItem *)sender
{
  [self preventReAddingToList];
  [self sendAddToReadingList];
  [self showAddFeedback];
}

- (void)showAddFeedback {
  NSString *text = self.authorLabel.text;
  UIColor *color = self.authorLabel.textColor;
  [UIView transitionWithView:self.authorLabel duration:0.3f options:UIViewAnimationOptionTransitionCrossDissolve animations:^{
    self.authorLabel.text = @"Added to reading list!";
    self.authorLabel.textColor = [UIColor purpleColor];
  } completion:nil];
  NSString *title = self.titleLabel.text; // Save this.
  dispatch_queue_t pause_queue = dispatch_queue_create("timer", NULL);
  dispatch_async(pause_queue, ^{
    [NSThread sleepForTimeInterval:1.5f];
    dispatch_async(dispatch_get_main_queue(), ^{
      if (self.titleLabel.text == title) { // If we still care about the same book...
        [UIView transitionWithView:self.authorLabel duration:0.3f options:UIViewAnimationOptionTransitionCrossDissolve animations:^{
          self.authorLabel.text = text; //Reset.
          self.authorLabel.textColor = color;
        } completion:nil];
      }
    });
  });
}

- (void)sendAddToReadingList {
  NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
  NSString *username =[defaults objectForKey:@"userEmail"];
  NSString *isbn = self.book.myISBN;
  NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:@"http://54.187.70.205/API/index.php/addBookToReadingList"]];
  request.HTTPMethod = @"POST";
  [request setValue:@"application/x-www-form-urlencoded; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
  NSString *stringData = [NSString stringWithFormat:@"email=%@&isbn=%@", username, isbn];
  NSData *requestBodyData = [stringData dataUsingEncoding:NSUTF8StringEncoding];
  request.HTTPBody = requestBodyData;
  [NSURLConnection sendAsynchronousRequest:request queue:[NSOperationQueue mainQueue] completionHandler:^(NSURLResponse *response, NSData *data, NSError *connectionError){
    NSLog(@"Post error? %@", connectionError);
  }];
}

#pragma mark User Preference

- (void)sendBookRating:(BookupPreferenceValue) preference {
  NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
  NSString *username = [defaults objectForKey:@"userEmail"];
  NSString *isbn = self.book.myISBN;
  NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:@"http://54.187.70.205/API/index.php/submitBookFeedback"]];
  request.HTTPMethod = @"POST";
  [request setValue:@"application/x-www-form-urlencoded; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
  NSString *stringData = [NSString stringWithFormat:@"email=%@&isbn=%@&rating=%ld", username, isbn, preference];
  NSData *requestBodyData = [stringData dataUsingEncoding:NSUTF8StringEncoding];
  request.HTTPBody = requestBodyData;
  [NSURLConnection sendAsynchronousRequest:request queue:[NSOperationQueue mainQueue] completionHandler:^(NSURLResponse *response, NSData *data, NSError *connectionError) {
    NSError *error;
    //NSLog(@"data: %@", [NSJSONSerialization JSONObjectWithData:data options:0 error:&error]);
    NSLog(@"Post error? %@", connectionError);
  }];
}

- (void) showPreferenceFeedback:(BookupPreferenceValue) preference {
  NSString *feedback = @"?";
  UIColor *color = [UIColor blackColor];
  if (preference == BookupPreferenceValueDislike) {
    feedback = @"Disliked!";
    color = [UIColor redColor];
  }
  if (preference == BookupPreferenceValueLike) {
    feedback = @"Liked!";
    color = [UIColor greenColor];
  }
  self.authorLabel.text = feedback;
  self.authorLabel.textColor = color;
}

- (void)dislike {
  [self sendBookRating:BookupPreferenceValueDislike];
  [self showPreferenceFeedback:BookupPreferenceValueDislike];
}

- (void)like {
  [self sendBookRating:BookupPreferenceValueLike];
  [self showPreferenceFeedback:BookupPreferenceValueLike];
}

- (IBAction)swipeLeft:(UISwipeGestureRecognizer *)sender {
  [self dislike];
  [self getABook];
}

- (IBAction)swipeRight:(id)sender {
  NSLog(@"Swiped right!" );
  [self like];
  [self getABook];
}

- (IBAction)pressLike:(id)sender {
  [self like];
  [self getABook];
}

- (IBAction)pressDislike:(id)sender {
  [self dislike];
  [self getABook];
}

#pragma mark Other

- (IBAction)appInfo:(id)sender {
  UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"Bookup for iPhone" message:@"\nCopyright © 2014.\n\nEthan Busbee\nKatherine Habeck\nArya McCarthy" preferredStyle:UIAlertControllerStyleAlert];
  UIAlertAction *cancel = [UIAlertAction actionWithTitle:@"Thanks, guys!" style:UIAlertActionStyleCancel handler:nil];
  [alert addAction:cancel];
  [self presentViewController:alert animated:YES completion:nil];
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

- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
  // The request is complete and data has been received
  // You can parse the stuff in your instance variable now

  [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
  NSError *parseError;
  NSDictionary *resultsFromJSON = [NSJSONSerialization JSONObjectWithData:_responseData options:0 error:&parseError];

  NSArray *bookArray = resultsFromJSON[@"books"];
  NSDictionary *this_book = [bookArray firstObject];

  NSString *title = this_book[@"title"];
  NSArray *authors = [this_book[@"author"] componentsSeparatedByString:@", "];
  NSString *descr = this_book[@"description"];
  NSURL *imageURL = [NSURL URLWithString:this_book[@"thumbnail"]];
  NSString *isbn = this_book[@"isbn"];
  NSLog(@"%@", this_book);

  self.book.myTitle = title;
  self.book.myAuthors = authors;
  self.book.myDescription = descr;
  self.book.myImageURL = imageURL;
  self.book.myISBN = isbn;

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
