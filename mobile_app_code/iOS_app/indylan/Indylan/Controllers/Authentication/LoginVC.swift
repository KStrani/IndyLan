//
//  LoginVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

import GoogleSignIn
import FacebookLogin
import AuthenticationServices

class LoginVC: UIViewController, UITextFieldDelegate, UINavigationControllerDelegate, GIDSignInDelegate, FooterLineable {

    @IBOutlet var scrView: BaseScrollView!
    
    @IBOutlet var tfEmail: ThemeTextField!
    
    @IBOutlet var tfPassword: ThemeTextField!
    
    @IBOutlet var viewBtn: UIStackView!
    
    @IBOutlet var btnForgotPwd: UIButton!
   
    @IBOutlet var btnLogin: ThemeButton!
    
    @IBOutlet var btnRegister: ThemeButton!
    
    @IBOutlet var svSocialButtons: UIStackView!
    
    @IBOutlet var btnFBLogin: SocialButton!
    
    @IBOutlet var btnGoogleSignIn: SocialButton!
    
    @IBOutlet var btnContinueAsGuest: UIButton!

    override func viewDidLoad() {
        super.viewDidLoad()
        
        btnForgotPwd.setTitleColor(Colors.black, for: .normal)
        btnForgotPwd.titleLabel?.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
        
        btnContinueAsGuest.setTitleColor(Colors.red, for: .normal)
        btnContinueAsGuest.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 16)
        
        btnFBLogin.type = .facebook
        btnGoogleSignIn.type = .google
        
        btnContinueAsGuest.isHidden = isGuestUser

        navigationItem.backBarButtonItem = UIBarButtonItem(title: "", style: .plain, target: nil, action: nil)

//        if #available(iOS 13.0, *) {
//            setupAppleSinginButton()
//        }
        
        self.automaticallyAdjustsScrollViewInsets = false
        self.view.endEditing(true)
        
        addFooterView()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        navigationController?.navigationBar.tintColor = Colors.black
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        navigationController?.navigationBar.tintColor = Colors.white
    }
    
    @available(iOS 13.0, *)
    private func setupAppleSinginButton() {
        let btnAppleSignIn = ASAuthorizationAppleIDButton()
        btnAppleSignIn.addTarget(self, action: #selector(btnAppleSingnAction), for: .touchUpInside)
        svSocialButtons.addArrangedSubview(btnAppleSignIn)
    }

    @available(iOS 13.0, *)
    @objc private func btnAppleSingnAction() {
        let request = ASAuthorizationAppleIDProvider().createRequest()
        request.requestedScopes = [.fullName, .email]
        
        let controller = ASAuthorizationController(authorizationRequests: [request])
        controller.delegate = self
        controller.presentationContextProvider = self
        controller.performRequests()
    }
    
    // MARK: Button Pressed Actions
    
    @IBAction func btnGoogleAction(_ sender: SocialButton) {
        Indicator.show()
        
        GIDSignIn.sharedInstance()?.delegate = self
        GIDSignIn.sharedInstance()?.presentingViewController = self
        
        if GIDSignIn.sharedInstance()?.hasPreviousSignIn() ?? false {
            GIDSignIn.sharedInstance()?.signOut()
        }
        
        GIDSignIn.sharedInstance()?.signIn()
    }
    
    var isLoggedIn: Bool {
        !(AccessToken.current?.isExpired ?? true)
    }
    
    @IBAction func btnFacebookAction(_ sender: SocialButton) {
//        let loginManager = LoginManager()
//
//        guard !isLoggedIn else {
//            loginManager.logOut()
//            return
//        }
//
//        loginManager.logIn(permissions: [.publicProfile, .email], viewController: self) { result in
//            switch result {
//            case .failed(let error):
//                Log.error("Facebook Login Failed: \(error.localizedDescription)")
//            case .cancelled:
//                Log.error("Facebook Login Cancelled")
//            case .success:
//                Log.info(result)
//            }
//        }
        
        
//        let loginManager = LoginManager()
//
//        if AccessToken.current != nil
//        {
//            loginManager.logOut()
//        }
//
//        loginManager.logIn(permissions: [.email], viewController: self, completion:{ (loginResult) in
//            switch loginResult {
//
//            case .success(let grantedPermissions, let declinedPermissions, let token):
//                //Log.info("success \(grantedPermissions) \n \(declinedPermissions) \n \(token)")
//
//                UserProfile.fetch(userId: token.userId!, completion: { (objProfile) in
//                    //Log.info(objProfile)
//
//                    let connection = GraphRequestConnection()
//                    connection.add(GraphRequest(graphPath: "/me", parameters: [ "fields" : "name, id, first_name, last_name, email, picture.type(large)" ])) { httpResponse, result in
//                        switch result {
//                        case .success(let response):
//                            //Log.info("Graph Request Succeeded: \(response)")
//
//                            let resObj = response.dictionaryValue!
//
//                            var dictUserDetails = [String : String]()
//
//                            guard let userId = resObj["id"] else
//                            {
//                                SnackBar.show("Facebook sign in failed")
//                                return
//                            }
//
//                            guard let email = resObj["email"] else
//                            {
//                                SnackBar.show("Unable to retrieve email address")
//                                return
//                            }
//
//                            if let fName = resObj["name"]
//                            {
//                                let fullName = fName as! String
//                                dictUserDetails["first_name"] = fullName
//                            }
//
//                            dictUserDetails["social_id"] = userId as? String
//                            dictUserDetails["email"] = email as? String
//                            dictUserDetails["password"] = ""
//                            dictUserDetails["confirm_password"] = ""
//                            dictUserDetails["social_type"] = "1"
//                            dictUserDetails["profile_pic"] = "http://graph.facebook.com/\(dictUserDetails["social_id"]!)/picture?type=large"
//
//                            //Log.info("Success \(dictUserDetails)")
//                            self.socailLogin(dictParam: dictUserDetails)
//
//                        case .failed(let error): break
//                            //Log.info("Graph Request Failed: \(error)")
//                        }
//                    }
//                    connection.start()
//                })
//
//            case .cancelled:
//                //Log.info("Cancelled")
//                break
//
//            case .failed(_):
//                //Log.info("Failed")
//                break
//            }
//        })
    }
    
    @IBAction func btnForgotAction(_ sender: UIButton) {
        self.view.endEditing(true)

        let objForgotPwd = UIStoryboard.auth.instantiateViewController(withClass: ForgotPasswordVC.self)!
        self.navigationController?.pushViewController(objForgotPwd, animated: true)
    }
   
    @IBAction func btnLoginAction(_ sender: UIButton) {
        view.endEditing(true)
        
        guard !tfEmail.text!.isEmpty else {
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

        apiLoginCall()
    }
    
    @IBAction func btnRegisterAction(_ sender: UIButton) {
        self.view.endEditing(true)
        let objRegister = UIStoryboard.auth.instantiateViewController(withClass: RegisterVC.self)!
        self.navigationController?.pushViewController(objRegister, animated: true)
    }

    @IBAction func btnContinueAsGuestAction(_ sender: UIButton) {
        UserDefaults.standard.set(UUID, forKey: "temp_user_id")
        AppDelegate.shared.setRootController()
    }

// MARK: Function
    
    func sign(_ signIn: GIDSignIn!, didSignInFor user: GIDGoogleUser!,
              withError error: Error!) {
        
        Indicator.hide()
        
        if error == nil {
            var dictUserDetails = [String : String]()
            
            guard let userId = user.userID else {
                SnackBar.show("Google sign in failed")
                return
            }

            guard let email = user.profile.email else {
                SnackBar.show("Unable to retrieve email address")
                return
            }
            
            if let fullName = user.profile.name {
                dictUserDetails["first_name"] = fullName
                dictUserDetails["last_name"] = fullName
            }
            
            if user.profile.hasImage {
                dictUserDetails["social_pic"] = user.profile.imageURL(withDimension: 200).absoluteString
            }
            
            dictUserDetails["social_id"] = userId
            dictUserDetails["email"] = email
            dictUserDetails["password"] = ""
            dictUserDetails["confirm_password"] = ""
            dictUserDetails["social_type"] = "2"
            
            //Log.info("Success \(dictUserDetails)")
            
            socailLogin(dictParam: dictUserDetails)
        } else {
            Log.info("Google SignIn Cancelled")
        }
    }
    
    func sign(_ signIn: GIDSignIn!, didDisconnectWith user: GIDGoogleUser!,
              withError error: Error!) {
        Log.info("Google SignOut")
    }
    
    func socailLogin(dictParam: [String: Any]) {
        if ReachabilityManager.shared.isReachable
        {
            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/register") as String, params: dictParam, completion:
                { (status, resObj) in

                    if status == true
                    {
                        if (resObj["status"].intValue) == 1
                        {
                            if let data = resObj["result"].dictionaryObject {
                                UserDefaults.standard.setValue(data.nullKeyRemoval, forKey: "kUserSession")
                            }
 
                            if isGuestUser {
//                                let userId = resObj["result"]["user_id"].stringValue
                                
//                                self.syncScoreWithUserId(userId)
                                
                                UserDefaults.standard.removeObject(forKey: "temp_user_id")
                                AppDelegate.shared.setRootController()
                                
                            } else {
                                UserDefaults.standard.removeObject(forKey: "temp_user_id")

                                AppDelegate.shared.setRootController()
                            }
                        }
                        else
                        {
                            SnackBar.show(resObj["message"].stringValue)
                        }
                    }
                    else
                    {
                        if resObj["message"].stringValue.count > 0
                        {
                            SnackBar.show("\(resObj["message"])")
                        }
                        else
                        {
                            SnackBar.show("serverTimeout".localized())
                        }
                    }
            })
        }
        else
        {
            SnackBar.show("noInternet".localized())
        }
    }

    private func apiLoginCall() {
        
        if ReachabilityManager.shared.isReachable {
            
            let dictParam = ["email" : tfEmail.text!, "password" : tfPassword.text!]
            
            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/login") as String, params: dictParam, completion:
                
                { (status, resObj) in
                    
                    if status
                    {
                        if (resObj["status"].intValue) == 1 {
                            
                            if let data = resObj["result"].dictionaryObject {
                                UserDefaults.standard.setValue(data, forKey: "kUserSession")
                            }
                            
                            if isGuestUser {
                                
//                                let userId = resObj["result"]["user_id"].stringValue
                                
//                                self.syncScoreWithUserId(userId)
                                
                                UserDefaults.standard.removeObject(forKey: "temp_user_id")
                                AppDelegate.shared.setRootController()
                                
                            } else {
                                
                                UserDefaults.standard.removeObject(forKey: "temp_user_id")

                                AppDelegate.shared.setRootController()
                            }
                        }
                        else {
                            SnackBar.show("\(resObj["message"])")
                        }
                    } else {
                        if resObj["message"].stringValue.count > 0 {
                            SnackBar.show("\(resObj["message"])")
                        } else {
                            SnackBar.show("serverTimeout".localized())
                        }
                    }
            })
        } else {
            SnackBar.show("No internet connection".localized())
        }
    }
    
    func syncScoreWithUserId(_ userId: String)
    {
        if ReachabilityManager.shared.isReachable
        {
            let dictParam = [
                "temp_id": UserDefaults.standard.value(forKey: "temp_user_id")!,
                "user_id": userId
            ]

            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/update_user_id_after_login") as String, params: dictParam, completion:
                { (status, resObj) in
                    
                    if status == true
                    {
                        if (resObj["status"].intValue) == 1
                        {
                            UserDefaults.standard.removeObject(forKey: "temp_user_id")
                            
                            AppDelegate.shared.setRootController()
                        }
                        else
                        {
                            SnackBar.show("\(resObj["message"])")
                        }
                    }
                    else
                    {
                        if resObj["message"].stringValue.count > 0
                        {
                            SnackBar.show("\(resObj["message"])")
                        }
                        else
                        {
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
    
    func isValidEmail(testStr:String) -> Bool {
        let emailRegEx = "[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,64}"
        
        let emailTest = NSPredicate(format:"SELF MATCHES %@", emailRegEx)
        return emailTest.evaluate(with: testStr)
    }
    
    // MARK: Text Field Delegate Methods

    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        
        switch textField {
       
        case tfEmail:
            tfPassword.becomeFirstResponder()
            
        case tfPassword:
            tfPassword.resignFirstResponder()
            
        default:
            self.view.endEditing(true)
        }
       
        return true
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}

@available(iOS 13.0, *)
extension LoginVC: ASAuthorizationControllerDelegate, ASAuthorizationControllerPresentationContextProviding {
    
    func authorizationController(controller: ASAuthorizationController, didCompleteWithAuthorization authorization: ASAuthorization) {
        if let appleIDCredential = authorization.credential as? ASAuthorizationAppleIDCredential {
            let userID = appleIDCredential.user

            // optional, might be nil
            let email = appleIDCredential.email

            // optional, might be nil
            let givenName = appleIDCredential.fullName?.givenName

            // optional, might be nil
            let familyName = appleIDCredential.fullName?.familyName

            // optional, might be nil
            let nickName = appleIDCredential.fullName?.nickname
        }
    }
    
    func authorizationController(controller: ASAuthorizationController, didCompleteWithError error: Error) {
        Log.error("authorization error")
        guard let error = error as? ASAuthorizationError else {
            return
        }

        switch error.code {
        case .canceled:
            // user press "cancel" during the login prompt
            Log.error("Canceled")
        case .unknown:
            // user didn't login their Apple ID on the device
            Log.error("Unknown")
        case .invalidResponse:
            // invalid response received from the login
            Log.error("Invalid Respone")
        case .notHandled:
            // authorization request not handled, maybe internet failure during login
            Log.error("Not handled")
        case .failed:
            // authorization failed
            Log.error("Failed")
        @unknown default:
            Log.error("Default")
        }
    }
    
    func presentationAnchor(for controller: ASAuthorizationController) -> ASPresentationAnchor {
        view.window!
    }
}
