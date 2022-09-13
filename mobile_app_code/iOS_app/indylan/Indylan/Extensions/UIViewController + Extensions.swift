//
//  UIViewController + Extensions.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import SDWebImage

extension UIViewController {

    func addBackButton() {
        let leftBarButton = UIBarButtonItem(image: UIImage(named: "back"), style: .done, target: self, action: nil)
        
        leftBarButton.actionClosure = {
            self.backButtonAction()
        }
        
        navigationItem.leftBarButtonItem = leftBarButton
    }
    
    @objc func backButtonAction() {
        self.navigationController?.popViewController(animated: true)
    }
    
    func removeBackButton() {
        navigationItem.leftBarButtonItem = nil
        navigationItem.hidesBackButton = true
    }
    
    func addProfileButton(image: UIImage? = nil) {
        if let image = image {
            navigationItem.rightBarButtonItem = getProfileBarButtonWith(image)
            return
        }
        
        if let user = currentUser, !user.profilePic.isEmpty {
            if let image = SDImageCache.shared.imageFromDiskCache(forKey: user.profilePic) {
                navigationItem.rightBarButtonItem = getProfileBarButtonWith(image)
            } else {
                navigationItem.rightBarButtonItem = getProfileBarButtonWith(#imageLiteral(resourceName: "add_profile_icon"))
                
                SDWebImageManager.shared.loadImage(with: URL(string: user.profilePic), options: .highPriority, progress: nil) { (image, data, error, cacheType, isFinished, imageUrl) in
                    if let image = image {
                        self.navigationItem.rightBarButtonItem = self.getProfileBarButtonWith(image)
                    }
                }
            }
        } else {
            navigationItem.rightBarButtonItem = getProfileBarButtonWith(#imageLiteral(resourceName: "add_profile_icon"))
        }
    }
    
    private func getProfileBarButtonWith(_ image: UIImage) -> UIBarButtonItem {
        let profileImage = image.withSize(CGSize(width: 35, height: 35)).hexagonImageWithBorder(width: 4, color: Colors.red).withRenderingMode(.alwaysOriginal)
        
        let rightBarButton = UIBarButtonItem(image: profileImage, style: .done, target: self, action: nil)
        
        rightBarButton.actionClosure = {
            self.btnProfileAction()
        }
        
        return rightBarButton
    }
    
    func removeProfileButton() {
        navigationItem.rightBarButtonItem = nil
    }
    
    private func btnProfileAction() {
        guard !isGuestUser else {
            let objlogin = UIStoryboard.auth.instantiateViewController(withClass: LoginVC.self)!
            objlogin.addBackButton()
            navigationController?.pushViewController(objlogin, animated: true)
            return
        }
        
        guard ReachabilityManager.shared.isReachable else {
            SnackBar.show("noInternet".localized())
            return
        }
    
        let objProfile = UIStoryboard.auth.instantiateViewController(withClass: ProfileVC.self)!
        objProfile.isProfileController = true
        self.navigationController?.pushViewController(objProfile, animated: true)
    }
    
    func addLogoutButton(tapAction: (() ->Void)? = nil) {
        let rightBarButton = UIBarButtonItem(image: UIImage(named:"log_out"), style: .done, target: self, action: nil)
        
        rightBarButton.actionClosure = tapAction
        
        navigationItem.rightBarButtonItem = rightBarButton
    }
}
