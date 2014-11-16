//
//  ViewController.m
//  Login
//
//  Created by Katherine Habeck on 11/12/14.
//  Copyright (c) 2014 Katherine Habeck. All rights reserved.
//

#import "LoginViewController.h"

@interface LoginViewController ()
@property (strong, nonatomic) IBOutlet UITextField *emailText;
@end

@implementation LoginViewController

- (BOOL) textFieldShouldReturn:(UITextField *)textField
{
  if (textField == self.emailText) {
    [self.passwordText becomeFirstResponder];
  }
  else if (textField == self.passwordText) {
    [textField resignFirstResponder];
    [self submitButton:nil];
  }
  return YES;
}

- (void)viewDidLoad {
    [super viewDidLoad];
    
    
    
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)submitButton:(id)sender {
    
    NSInteger success = 0;
    NSInteger loginResults =0;
    @try {
        //check for non-empty text fields
        if([[self.emailText text] isEqualToString:@""] || [[self.passwordText text] isEqualToString:@""] ) {
            [self alertStatus:@"Please enter both your email and password" :@"Invalid Login" :0];
        }
        
        else {
            NSString *post =[[NSString alloc] initWithFormat:@"http://localhost:8888/API/index.php/validate/%@/%@",[self.emailText text],[self.passwordText text]];
            NSLog(@"PostData: %@",post);
            
            NSMutableURLRequest *request =
            [NSMutableURLRequest requestWithURL:[NSURL URLWithString:post]
                                    cachePolicy:NSURLRequestReloadIgnoringLocalAndRemoteCacheData
                                timeoutInterval:10];
            [request setHTTPMethod:@"GET"];
            [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:YES];
            NSURLConnection *conn = [[NSURLConnection alloc] initWithRequest:request delegate:self];
            
            NSError *error = [[NSError alloc] init];
            NSHTTPURLResponse *response = nil;
            NSData *urlData=[NSURLConnection sendSynchronousRequest:request returningResponse:&response error:&error];
          [[UIApplication sharedApplication] setNetworkActivityIndicatorVisible:NO];
            NSLog(@"Response code: %ld", (long)[response statusCode]);
            
            if ([response statusCode] >= 200 && [response statusCode] < 300)
            {
                NSString *responseData = [[NSString alloc]initWithData:urlData encoding:NSUTF8StringEncoding];
                NSLog(@"Response ==> %@", responseData);
                
                NSError *error = nil;
                NSDictionary *jsonData = [NSJSONSerialization
                                          JSONObjectWithData:urlData
                                          options:NSJSONReadingMutableContainers
                                          error:&error];
                
                loginResults = [jsonData[@"valid"] integerValue];
                NSLog(@"HEY ==> %ld", (long)loginResults);
                
                success = [jsonData[@"success"] integerValue];
                NSLog(@"Success: %ld",(long)success);
                
                if( success ==1 && loginResults==1)
                {
                    NSLog(@"Login SUCCESS");
                } else {
                    
                    NSString *error_msg = (NSString *) jsonData[@"error_message"];
                    [self alertStatus:error_msg :@"Please check that you entered valid login information." :0];
                }
            }
            
            
        }
    }
    @catch (NSException * e) {
        NSLog(@"Exception: %@", e);
        [self alertStatus:@"Sign in Failed." :@"Error!" :0];
    }
    if (success==1 &&loginResults==1 ) {
        NSString *email=[self.emailText text];
        NSLog(@"email %@", email);
        NSLog(@"successful login");
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        [defaults setValue:email forKey:@"userEmail"];
        [defaults synchronize];
        [self performSegueWithIdentifier:@"login_success" sender:self];
    }
}

- (void) alertStatus:(NSString *)msg :(NSString *)title :(int) tag
{
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:title
                                                        message:msg
                                                       delegate:self
                                              cancelButtonTitle:@"Ok"
                                              otherButtonTitles:nil, nil];
    alertView.tag = tag;
    [alertView show];
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
    NSLog(@"%@", @"Did finish loading.");
    
}


- (IBAction)backgroundTap:(id)sender {
    [self.view endEditing:YES];
}

@end
