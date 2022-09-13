//
//  RegisterVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import Alamofire

class RegisterVC: ThemeViewController, UITextFieldDelegate {
    
    // MARK: Declarations
    
    @IBOutlet var scrView: BaseScrollView!
    
    @IBOutlet var imgViewProfile: ProfileImageView!
    
    @IBOutlet var tfName: ThemeTextField!
    
    @IBOutlet var tfEmail: ThemeTextField!
    
    @IBOutlet var tfPassword: ThemeTextField!
    
    @IBOutlet var tfConfirmPassword: ThemeTextField!
    
    @IBOutlet var btnRegister: ThemeButton!
    
    @IBOutlet var btnPrivacy: UIButton!
    
    @IBOutlet var viewBtnPrivacyBottom: UIView!

    @IBOutlet var btnAbout: UIButton!
    
    @IBOutlet var viewBtnAboutBottom: UIView!

    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.automaticallyAdjustsScrollViewInsets = false
        
        self.title = "REGISTRATION"
        
        removeProfileButton()
        setupProfileImage()
        
        btnPrivacy.setTitleColor(Colors.black, for: .normal)
        btnPrivacy.titleLabel?.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
        
        viewBtnPrivacyBottom.backgroundColor = Colors.black
        
        btnAbout.setTitleColor(Colors.black, for: .normal)
        btnAbout.titleLabel?.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
        
        viewBtnAboutBottom.backgroundColor = Colors.black
    }

    func setupProfileImage() {
        imgViewProfile.contentMode = .scaleAspectFill
        imgViewProfile.placeHolderImage = #imageLiteral(resourceName: "add_profile_icon")
    }

    //MARK: Functions
    
    func actionGoToSetting(action: UIAlertAction)
    {
        if let appSettings = URL(string: UIApplicationOpenSettingsURLString + Bundle.main.bundleIdentifier!)
        {
            if UIApplication.shared.canOpenURL(appSettings) {
                if #available(iOS 10.0, *) {
                    UIApplication.shared.open(appSettings)
                } else {
                    UIApplication.shared.openURL(appSettings)
                }
            }
        }
    }

    func registeruser()
    {
        if ReachabilityManager.shared.isReachable
        {
            Indicator.show()
            
            let image = imgViewProfile.image!

            let isImageAvailable = !image.isEqualToImage(image: #imageLiteral(resourceName: "add_profile_icon"))
            
            let parameters = [
                "first_name"        : tfName.text!,
                "email"             : tfEmail.text!,
                "password"          : tfPassword.text!,
                "confirm_password"  : tfConfirmPassword.text!,
                "social_id"         : "",
                "social_type"       : "0"
            ]

            Log.server("Parameters: \(parameters)\n")
            
            Alamofire.upload( multipartFormData: { MultipartFormData in
                
                for (key, value) in parameters {
                    MultipartFormData.append(value.data(using: .utf8)!, withName: key)
                }

                if isImageAvailable {
                    let resizedImage = image.cropsToSquare().withSize(CGSize(width: 500, height: 500))
                    
                    MultipartFormData.append(UIImageJPEGRepresentation(resizedImage, 1)!, withName: "profile_pic", fileName: "photo.png", mimeType: "image/png")
                }
                
            }, to: "\(Environment.APIPath)/register") { (result) in
                
                Log.server("URL: \("\(Environment.APIPath)/register")\n")
                
                switch result {
                    
                case .success(let upload, _, _):
                    
                    upload.responseJSON { response in
                        Indicator.hide()
                        
                        switch response.result
                        {
                        case .success:

                            let resObj = JSON(response.result.value!)
                            
                            Log.server(resObj)
                            
                            SnackBar.show("\(resObj["message"])")
                            
                            if (resObj["status"].intValue) == 1
                            {
                                if let data = resObj["result"].dictionaryObject {
                                    UserDefaults.standard.setValue(data, forKey: "kUserSession")
                                }
                                
                                if isGuestUser {
                                    
//                                    let userId = resObj["result"]["user_id"].stringValue
//
//                                    self.syncScoreWithUserId(userId)
                                    
                                    UserDefaults.standard.removeObject(forKey: "temp_user_id")
                                    AppDelegate.shared.setRootController()
                                    
                                } else {
                                    
                                    UserDefaults.standard.removeObject(forKey: "temp_user_id")
                                   
                                    AppDelegate.shared.setRootController()
                                }
                            }
                            
                            break
                            
                        case .failure(let err):
                            SnackBar.show(err.localizedDescription)
                            break
                        } 
                    }
                    
                case .failure:
                    Indicator.hide()
                    break
                }
            }
        }
        else
        {
            Indicator.hide()
            SnackBar.show("No internet connection".localized())
        }
    }
    
    func syncScoreWithUserId(_ userId: String){
        if ReachabilityManager.shared.isReachable{
            let dictParam = [
                "temp_id": UserDefaults.standard.value(forKey: "temp_user_id")!,
                "user_id": userId
            ]

            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/update_user_id_after_login") as String, params: dictParam, completion:
                { (status, resObj) in
                    
                    if status == true {
                        if (resObj["status"].intValue) == 1 {
                            UserDefaults.standard.removeObject(forKey: "temp_user_id")
                            AppDelegate.shared.setRootController()
                        } else {
                            SnackBar.show("\(resObj["message"])")
                        }
                    }
                    else{
                        if resObj["message"].stringValue.count > 0{
                            SnackBar.show("\(resObj["message"])")
                        }
                        else {
                            SnackBar.show("serverTimeout".localized())
                        }
                    }
            })
        }
        else
        {
            SnackBar.show("No internet connection".localized())
        }
    }
    
    func isValidEmail(testStr:String) -> Bool{
        let emailRegEx = "[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,64}"
        let emailTest = NSPredicate(format:"SELF MATCHES %@", emailRegEx)
        return emailTest.evaluate(with: testStr)
    }
    
    // MARK: Button Pressed Actions
    
    @IBAction func btnRegisterEvent(_ sender: ThemeButton){
        self.view.endEditing(true)
        guard (tfName.text?.count)! > 0 else {
            SnackBar.show("Please enter your name")
            TapticEngine.notification.feedback(.error)
            tfName.shake()
            return
        }
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
        guard (tfPassword.text?.count)! > 0 else {
            SnackBar.show("Please enter password")
            TapticEngine.notification.feedback(.error)
            tfPassword.shake()
            return
        }
        guard (tfConfirmPassword.text?.count)! > 0 else {
            SnackBar.show("Please enter confirm password")
            TapticEngine.notification.feedback(.error)
            tfConfirmPassword.shake()
            return
        }
        guard tfPassword.text == tfConfirmPassword.text else {
            SnackBar.show("Password and confirm password donot match")
            TapticEngine.notification.feedback(.error)
            tfConfirmPassword.shake()
            return
        }
        registeruser()
    }
    
    @IBAction func btnPrivacyEvent(_ sender: Any) {
        guard ReachabilityManager.shared.isReachable else {
            SnackBar.show("noInternet".localized())
            return
        }
        let objWebView = UIStoryboard.home.instantiateViewController(withClass: WebViewVC.self)!
        objWebView.strLink = "https://indylan.eu/privacy-policy/"
        objWebView.strTitle = "Privacy Policy"
        self.navigationController?.pushViewController(objWebView, animated: true)
    }
    @IBAction func btnTermsEvent(_ sender: Any) {
        guard ReachabilityManager.shared.isReachable else {
            SnackBar.show("noInternet".localized())
            return
        }
        let objWebView = UIStoryboard.home.instantiateViewController(withClass: WebViewVC.self)!
        objWebView.strLink = "https://indylan.eu/terms-conditions/"
        objWebView.strTitle = "Terms & Conditions"
        self.navigationController?.pushViewController(objWebView, animated: true)
    }
    // MARK: Text Field Delegate Methods

    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        switch textField {
        case tfName:
            tfEmail.becomeFirstResponder()
        case tfEmail:
            tfPassword.becomeFirstResponder()
        case tfPassword:
            tfConfirmPassword.becomeFirstResponder()
        case tfConfirmPassword:
            btnRegisterEvent(btnRegister)
        default:
            self.view.endEditing(true)
        }
        return true
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
    }
}
