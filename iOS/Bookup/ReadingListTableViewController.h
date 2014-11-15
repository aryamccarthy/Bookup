//
//  ReadingListTableViewController.h
//  Bookup
//
//  Created by Arya McCarthy on 11/10/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ReadingListTableViewController : UITableViewController<UITableViewDelegate, UITableViewDataSource>
@property (strong, nonatomic) NSArray *books; // of Book objects

@end
