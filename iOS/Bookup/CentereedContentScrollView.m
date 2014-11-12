//
//  CentereedContentScrollView.m
//  Bookup
//
//  Created by Arya McCarthy on 11/10/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import "CentereedContentScrollView.h"

@implementation CentereedContentScrollView

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect {
    // Drawing code
}
*/

- (void)layoutSubviews {
  [super layoutSubviews];

  // center the image as it becomes smaller than the size of the screen
  CGSize boundsSize = self.bounds.size;
  CGRect frameToCenter = _tileContainerView.frame;
  //NSLog(@"BLERG SIZE IS %@", NSStringFromCGSize(boundsSize));
  //NSLog(@"FLR FRAME %@", NSStringFromCGRect(frameToCenter));
  // center horizontally
  if (frameToCenter.size.width < boundsSize.width)
    frameToCenter.origin.x = (boundsSize.width - frameToCenter.size.width) / 2;
  else
    frameToCenter.origin.x = 0;

  // center vertically
  if (frameToCenter.size.height < boundsSize.height)
    frameToCenter.origin.y = (boundsSize.height - frameToCenter.size.height) / 2;
  else
    frameToCenter.origin.y = 0;

  _tileContainerView.frame = frameToCenter;
  //NSLog(@"updated to %@", NSStringFromCGRect(frameToCenter));
}

@end
