//
//  CentereedContentScrollView.h
//  Bookup
//
//  Created by Arya McCarthy on 11/10/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface CentereedContentScrollView : UIScrollView
@property (weak, nonatomic, setter=setImageView:) UIImageView *tileContainerView;
@end
