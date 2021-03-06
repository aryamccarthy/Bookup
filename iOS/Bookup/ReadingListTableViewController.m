//
//  ReadingListTableViewController.m
//  Bookup
//
//  Created by Arya McCarthy on 11/10/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import "ReadingListTableViewController.h"
#import "Book.h"
@interface ReadingListTableViewController ()
@property (strong, nonatomic) NSNumber *numberOfRows;
@end

@implementation ReadingListTableViewController

- (NSNumber *) numberOfRows
{
  if (!_numberOfRows) {
    _numberOfRows = [NSNumber numberWithInt:0];
  }
  return _numberOfRows;
}

- (void) setBooks:(NSArray *)books
{
  NSLog(@"%@", @"setBooks called.");
  _books = books;
  [self.tableView reloadData];
}
- (IBAction)getInfo:(id)sender {
  UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"Bookup for iPhone" message:@"\nCopyright © 2014.\n\nEthan Busbee\nKatherine Habeck\nArya McCarthy" preferredStyle:UIAlertControllerStyleAlert];
  UIAlertAction *cancel = [UIAlertAction actionWithTitle:@"Thanks, guys!" style:UIAlertActionStyleCancel handler:nil];
  [alert addAction:cancel];
  [self presentViewController:alert animated:YES completion:nil];
}

- (void)viewDidLoad
{
  [super viewDidLoad];
  NSLog(@"%@", @"Table View did load.");

  [self refresh];

  // Uncomment the following line to preserve selection between presentations.
  // self.clearsSelectionOnViewWillAppear = NO;

  // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
  // self.navigationItem.rightBarButtonItem = self.editButtonItem;
}

- (void)viewWillAppear:(BOOL)animated {
  [super viewWillAppear:animated];
  [self.tableView reloadData];
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

- (void) fetchBooks
{
  NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
  NSString *userEmail = [defaults objectForKey:@"userEmail"];
  NSLog(@"%@", userEmail);
  NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"http://54.187.70.205/API/index.php/getReadingList/%@", userEmail]];
  NSError *error;
  NSData *json = [NSData dataWithContentsOfURL:url options:0 error:&error];
  if (!json) // This stops crashing that occurs when there is no network connection. Instead, it just stops trying and carries on with its little sofware life.
    return;

  NSDictionary *resultsFromJSON = [NSJSONSerialization JSONObjectWithData:json options:0 error:&error];
  NSArray *bookArray = resultsFromJSON[@"books"];
  self.numberOfRows = [NSNumber numberWithInteger:[bookArray count]];// !!! IMPORTANT: update number of table rows so ANYTHING shows up.
  NSMutableArray *result = [NSMutableArray new];
  for (int i = 0; i < [bookArray count]; ++i) {
    NSDictionary *this_book = bookArray[i];

    NSString *title = this_book[@"title"];
    NSArray *authors = [this_book[@"author"] componentsSeparatedByString:@", "];
    NSString *descr = this_book[@"description"];
    NSURL *imageURL = [NSURL URLWithString:this_book[@"thumbnail"]];
    NSString *isbn = this_book[@"isbn"];

    Book *b = [Book new];
    b.myTitle = title;
    b.myAuthors = authors;
    b.myDescription = descr;
    b.myImageURL = imageURL;
    b.myISBN = isbn;
    [result addObject:b];
  }
  //NSLog(@"Results: %@", resultsFromJSON);
  self.books = result;
}

- (IBAction)refresh
{
  [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:YES];
  [self.refreshControl beginRefreshing];
  dispatch_queue_t otherQ = dispatch_queue_create("Q", NULL);
  dispatch_async(otherQ, ^{
    [self fetchBooks];
    dispatch_async(dispatch_get_main_queue(), ^{
      [self.refreshControl endRefreshing];
      [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
    });
  });
}

- (void)didReceiveMemoryWarning
{
  [super didReceiveMemoryWarning];
  // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
  return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
  return [self.numberOfRows integerValue];
}

- (Book *) fetchNthBook:(NSUInteger)n
{
  id element = self.books[n];
  if (![element isKindOfClass:[Book class]]) {
    NSLog(@"The array is infected! index: %lu, element: %@", (unsigned long)n, element);
  }
  return (Book *)element;
}

- (NSString *) getMyTitleForRow:(NSUInteger)row inSection:(NSUInteger)section
{
  Book *b = [self fetchNthBook:row];
  return b.myTitle;
}

- (NSString *) getMySubtitleForRow:(NSUInteger)row inSection:(NSUInteger)section
{
  Book *b = [self fetchNthBook:row];
  return b.myAuthorsAsString;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
  UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:@"Reading List Entry" forIndexPath:indexPath];
  // Configure the cell.
  cell.textLabel.text = [self getMyTitleForRow:indexPath.row inSection:indexPath.section];
  cell.detailTextLabel.text = [self getMySubtitleForRow:indexPath.row inSection: indexPath.section];
  cell.imageView.image = [UIImage imageNamed:@"favicon-32x32"];
  return cell;
}


/*
 // Override to support conditional editing of the table view.
 - (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath {
 // Return NO if you do not want the specified item to be editable.
 return YES;
 }
 */

/*
 // Override to support editing the table view.
 - (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath {
 if (editingStyle == UITableViewCellEditingStyleDelete) {
 // Delete the row from the data source
 [tableView deleteRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationFade];
 } else if (editingStyle == UITableViewCellEditingStyleInsert) {
 // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
 }
 }
 */

/*
 // Override to support rearranging the table view.
 - (void)tableView:(UITableView *)tableView moveRowAtIndexPath:(NSIndexPath *)fromIndexPath toIndexPath:(NSIndexPath *)toIndexPath {
 }
 */

/*
 // Override to support conditional rearranging of the table view.
 - (BOOL)tableView:(UITableView *)tableView canMoveRowAtIndexPath:(NSIndexPath *)indexPath {
 // Return NO if you do not want the item to be re-orderable.
 return YES;
 }
 */


 #pragma mark - Navigation

 // In a storyboard-based application, you will often want to do a little preparation before navigation
 - (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
 // Get the new view controller using [segue destinationViewController].
 // Pass the selected object to the new view controller.
   if ([sender isKindOfClass:[UITableViewCell class]]) {
     NSIndexPath *indexPath = [self.tableView indexPathForCell:sender];
     if (indexPath) {
       if ([segue.identifier isEqualToString:@"Show Description"]) {
         if ([segue.destinationViewController respondsToSelector:@selector(setBook:)]) {
           Book *book = [self fetchNthBook:indexPath.row];
           [segue.destinationViewController performSelector:@selector(setBook:) withObject:book];
           [segue.destinationViewController setTitle:[self getMyTitleForRow:indexPath.row inSection:indexPath.section]];
         }
       }
     }
   }
 }


@end
