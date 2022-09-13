//
//  ProfileVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

import Alamofire
import GoogleSignIn
import FacebookLogin

class ProfileVC: ThemeViewController, UITextFieldDelegate, UIImagePickerControllerDelegate,
UINavigationControllerDelegate {

    @IBOutlet var scrView: BaseScrollView!
    
    @IBOutlet weak var vwTotalScore: UIView!
    
    @IBOutlet var imgViewProfile: ProfileImageView!
    
    @IBOutlet var lblScore: UILabel!
    
    @IBOutlet var tfName: UITextField!
    
    @IBOutlet var tfEmail: UITextField!
    
    @IBOutlet var btnChangePassword: UIButton!
    
    @IBOutlet var svChangePassword: UIStackView!
    @IBOutlet var svUpdatePassword: UIStackView!
    
    @IBOutlet var tfCurrentPwd: UITextField!
    
    @IBOutlet var tfNewpwd: UITextField!
    
    @IBOutlet var tfConfirmPwd: UITextField!
    
    @IBOutlet var btnUpdate: ThemeButton!

    @IBOutlet var btnRateUs: BaseButton!

    @IBOutlet var btnAboutUs: BaseButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        self.title = "PROFILE"

        self.automaticallyAdjustsScrollViewInsets = false
        self.view.endEditing(true)

        removeProfileButton()
        
        addLogoutButton {
            self.logoutUser()
        }

        svChangePassword.isHidden = true
        svUpdatePassword.isHidden = true
        svUpdatePassword.alpha = 0
        
        vwTotalScore.backgroundColor = Colors.yellow
        vwTotalScore.layer.cornerRadius = 8
        
        lblScore.textColor = Colors.black
        lblScore.font = Fonts.centuryGothic(ofType: .bold, withSize: 15)
        
        imgViewProfile.placeHolderImage = #imageLiteral(resourceName: "add_profile_icon")
        imgViewProfile.contentMode = .scaleAspectFill
        
        btnChangePassword.setTitleColor(Colors.black, for: .normal)
        btnChangePassword.titleLabel?.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
        
        btnRateUs.backgroundColor = Colors.skyBlue
        btnAboutUs.backgroundColor = Colors.blue

        setUserData()
        
        getUserinfo()
    }
    
    private func setUserData() {
        guard let user = currentUser else { return }
        
        tfName.text = user.firstName
        tfEmail.text = user.email
        lblScore.text = "Total Score: " + user.score

        if !user.socialPic.isEmpty {
            imgViewProfile.setImage(withURL: user.socialPic, AndPlaceholder: UIImage(named: "add_profile_icon"))
        }
        
        svChangePassword.isHidden = !(user.loginType == .normal)
        
        if !user.profilePic.isEmpty {
            imgViewProfile.setImage(withURL: user.profilePic, AndPlaceholder: UIImage(named: "add_profile_icon"))
        }
    }
    
    // MARK: Button Pressed Actions
    
    @IBAction func btnChangePwdEvent(_ sender: UIButton) {
        self.view.endEditing(true)
        
        UIView.animate(withDuration: 0.3) {
            self.svUpdatePassword.isHidden.toggle()
            self.svUpdatePassword.alpha = (self.svUpdatePassword.isHidden ? 0 : 1)
        }
        
        if svUpdatePassword.isHidden {
            self.tfCurrentPwd.text = ""
            self.tfNewpwd.text = ""
            self.tfConfirmPwd.text = ""
        }
    }
    
    @IBAction func btnUpdateEvent(_ sender: ThemeButton) {
        self.view.endEditing(true)
        
        guard (tfName.text?.count)! > 0 else {
            SnackBar.show("Please enter your name")
            TapticEngine.notification.feedback(.error)
            tfName.shake()
            return
        }
        
        guard !svUpdatePassword.isHidden else {
            updateProfile()
            return
        }
        
        guard (tfCurrentPwd.text?.count)! > 0 else {
            SnackBar.show("Please enter password")
            TapticEngine.notification.feedback(.error)
            tfCurrentPwd.shake()
            return
        }
        
        guard (tfNewpwd.text?.count)! > 0 else {
            SnackBar.show("Please enter new password")
            TapticEngine.notification.feedback(.error)
            tfNewpwd.shake()
            return
        }
       
        guard (tfConfirmPwd.text?.count)! > 0 else {
            SnackBar.show("Please enter confirm password")
            TapticEngine.notification.feedback(.error)
            tfConfirmPwd.shake()
            return
        }
        
        guard tfConfirmPwd.text == tfNewpwd.text else {
            SnackBar.show("Password and confirm password donot match")
            TapticEngine.notification.feedback(.error)
            tfConfirmPwd.shake()
            return
        }
        
        updateProfile()
    }
    
    @IBAction func btnRateUsEvent(_ sender: BaseButton) {
        if let url = URL(string: "https://apps.apple.com/us/app/indylan-learn-indigenous-langs/id1590288935"),
            UIApplication.shared.canOpenURL(url){
            if #available(iOS 10.0, *) {
                UIApplication.shared.open(url)
            } else {
                UIApplication.shared.openURL(url)
            }
        }
    }
    
  
    @IBAction func btnAboutUsEvent(_ sender: BaseButton) {
        let objAboutUs = UIStoryboard.auth.instantiateViewController(withClass: AboutUsVC.self)!
        self.navigationController?.pushViewController(objAboutUs, animated: true)
    }
    
    @objc func logoutUser() {
        Alert.showWith("Logout", message: "Are you sure, you want to logout?", positiveTitle: "Logout", negativeTitle: "Cancel") { isPositive in
            if isPositive {
                self.actionLogOut()
            }
        }
    }
    
    func actionLogOut() {
        guard let user = currentUser else { return }
        
        switch user.loginType {
        case .normal:
            break
        case .facebook:
            let loginManager = LoginManager()
            
            if AccessToken.current != nil {
                loginManager.logOut()
            }
            break
        case .google:
            GIDSignIn.sharedInstance().disconnect()
            break
        default: break
        }
        
        if let bundleId = Bundle.main.bundleIdentifier {
            UserDefaults.standard.removePersistentDomain(forName: bundleId)
            UserDefaults.standard.synchronize()
        }
        
        UIView.appearance().semanticContentAttribute = .forceLeftToRight
        navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
        
        selectedMenuLanguageId = "1"
        
        UserDefaults.standard.set("en", forKey: "selectedLanguageCode")
        UserDefaults.standard.synchronize()

        AppDelegate.shared.setRootController()
    }

    func getUserinfo() {
        
        if ReachabilityManager.shared.isReachable {
            guard let user = currentUser else { return }
            
            let dictParam = [
                "user_id": user.userId
            ]

            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/get_user_info") as String, params: dictParam, withLoader: false, completion:
                { (status, resObj) in
                    
                    if status == true
                    {
                        if (resObj["status"].intValue) == 1
                        {
                            if let data = resObj["result"].dictionaryObject {
                                UserDefaults.standard.setValue(data, forKey: "kUserSession")
                                
                                self.setUserData()
                            }
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
    
    func updateProfile()
    {
        if ReachabilityManager.shared.isReachable
        {
            guard let user = currentUser else { return }
            
            Indicator.show()
            
            let image = imgViewProfile.image!

            let isImageAvailable = !image.isEqualToImage(image: #imageLiteral(resourceName: "add_profile_icon"))
            
            var parameters = [
                "first_name"        : tfName.text!,
                "user_id"           : user.userId,
                "email"             : tfEmail.text!,
                "is_remove_pic"     : isImageAvailable ? "0" : "1",
            ]
            
            if !self.svChangePassword.isHidden && !self.svUpdatePassword.isHidden {
                parameters["current_password"]  = tfCurrentPwd.text!
                parameters["new_password"]      = tfNewpwd.text!
                parameters["con_new_password"]  = tfConfirmPwd.text!
            }
            
            Log.shortLine()
            Log.server("URL: \("\(Environment.APIPath)/edit_profile")\n")
            Log.shortLine()
            Log.server("Request Parameters:\n\n\(parameters as AnyObject)\n")
            
            Alamofire.upload( multipartFormData: { MultipartFormData in
                
                for (key, value) in parameters {
                    MultipartFormData.append((value as AnyObject).data(using: String.Encoding.utf8.rawValue)!, withName: key)
                }
                
                if isImageAvailable {
                    let resizedImage = image.cropsToSquare().withSize(CGSize(width: 500, height: 500))
                    
                    MultipartFormData.append(UIImageJPEGRepresentation(resizedImage, 1)!, withName: "profile_pic", fileName: "photo.png", mimeType: "image/png")
                }
                
            }, to: "\(Environment.APIPath)/edit_profile") { (result) in
                
                switch result {
                    
                case .success(let upload, _, _):
                    
                    upload.responseJSON { response in
                        
                        if let result = response.result.value {
                            Log.server(result)
                        }
                        
                        Indicator.hide()

                        switch response.result {
                        case .success:
                            let resObj = JSON(response.result.value!)
                            SnackBar.show("\(resObj["message"])")
                            
                            if let data = resObj["result"].dictionaryObject {
                                UserDefaults.standard.setValue(data, forKey: "kUserSession")
                            }
                            
                            let resizedImage = image.cropsToSquare().withSize(CGSize(width: 500, height: 500))
                            
                            NotificationCenter.default.post(name: .didUpdateProfilePicture, object: nil, userInfo: ["image": resizedImage])
                            
                            break
                        case .failure(let error):
                            SnackBar.show(error.localizedDescription)
                            break
                        }
                    }
                    
                case .failure(let encodingError):
                    Indicator.hide()
                    SnackBar.show("\(encodingError.localizedDescription)")
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
    
    // MARK: Text Field Delegate Methods
 
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        
        switch textField {

        case tfName:
            guard !svUpdatePassword.isHidden else {
                btnUpdateEvent(btnUpdate)
                break
            }
            tfCurrentPwd.becomeFirstResponder()
            break
            
        case tfCurrentPwd:
            tfNewpwd.becomeFirstResponder()
            break
            
        case tfNewpwd:
            tfConfirmPwd.becomeFirstResponder()
            break

        case tfConfirmPwd:
            btnUpdateEvent(btnUpdate)
            break

        default:
            textField.resignFirstResponder()
            break
        }
        return true
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
