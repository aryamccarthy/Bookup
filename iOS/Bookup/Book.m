//
//  Book.m
//  Bookup
//
//  Created by Arya McCarthy on 11/10/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import "Book.h"

@implementation Book

-(NSString *) myAuthorsAsString {
  return [self.myAuthors componentsJoinedByString:@", "];
}

- (NSString *)description
{
  return [NSString stringWithFormat:@"\nTitle: %@ \nAuthors: %@\nDescription = %@\
          \nURL: %@", self.myTitle, self.myAuthorsAsString, self.myDescription, self.myImageURL];
}


@end
