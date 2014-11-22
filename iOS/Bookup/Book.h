//
//  Book.h
//  Bookup
//
//  Created by Arya McCarthy on 11/10/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Book : NSObject
@property (strong, nonatomic) NSString *myTitle;
@property (strong, nonatomic) NSArray *myAuthors;
@property (strong, nonatomic) NSString *myDescription;
@property (strong, nonatomic) NSURL *myImageURL;
@property (strong, nonatomic) NSString *myISBN;
-(NSString *) myAuthorsAsString;
@end
