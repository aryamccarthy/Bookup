//
//  LoginViewController.h
//  Bookup
//
//  Created by Katherine Habeck on 11/15/14.
//  Copyright (c) 2014 Arya McCarthy. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface LoginViewController : UIViewController

@property (weak, nonatomic) IBOutlet UITextField *passwordText;
- (IBAction)submitButton:(id)sender;
- (IBAction)backgroundTap:(id)sender;
@end
