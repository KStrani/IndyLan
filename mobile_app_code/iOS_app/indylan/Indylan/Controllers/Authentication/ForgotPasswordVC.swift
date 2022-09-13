//
//  ForgotPasswordVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class ForgotPasswordVC: ThemeViewController, UITextFieldDelegate {

    @IBOutlet var tfEmail: ThemeTextField!
    
    @IBOutlet var btnForgotPwd: ThemeButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        self.title = "FORGOT PASSWORD"
        
        removeProfileButton()
    }

    // MARK: Button Pressed Actions
    
    func isValidEmail(testStr:String) -> Bool {
        let emailRegEx = "[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,64}"
        
        let emailTest = NSPredicate(format:"SELF MATCHES %@", emailRegEx)
        return emailTest.evaluate(with: testStr)
    }
    
    @IBAction func btnSendEvent(_ sender: ThemeButton) {
        self.view.endEditing(true)
        
        guard (tfEmail.text?.count)! > 0 else {
            SnackBar.show("Please enter your email address")
            TapticEngine.notification.feedback(.error)
            tfEmail.shake()
            return
        }
        
        guard isValidEmail(testStr: tfEmail.text!) else {
            SnackBar.show("Please enter valid email")
            TapticEngine.notification.feedback(.error)
            tfEmail.shake()
            return
        }
        
        if ReachabilityManager.shared.isReachable
        {
            let dictParam = ["email" : tfEmail.text!]

            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/forgot_password") as String, params: dictParam, completion:
                { (status, resObj) in
                    
                    SnackBar.show("\(resObj["message"])")

                    if status == true {
                        if (resObj["status"].intValue) == 1 {
                            DispatchQueue.main.asyncAfter(deadline: .now() + 2.0) {
                                self.navigationController?.popViewController(animated: true)
                            }
                        }
                    }
            })
        }
        else {
            SnackBar.show("No internet connection".localized())
        }
    }

    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
       
        textField.resignFirstResponder()
        btnSendEvent(btnForgotPwd)
        
        return true
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
