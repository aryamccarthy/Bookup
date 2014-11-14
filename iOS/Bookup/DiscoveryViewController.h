//
//  DiscoveryViewController.h
//  Bookup
//
//  Created by Arya McCarthy on 11/9/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface DiscoveryViewController : UIViewController<NSURLConnectionDelegate>
{
  NSMutableData *_responseData;
}
@end
