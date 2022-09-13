//
//  AppDelegate.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import GoogleSignIn
import FBSDKLoginKit
import IQKeyboardManagerSwift

@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate, UINavigationControllerDelegate {

    var window: UIWindow?
    
    static let shared = UIApplication.shared.delegate as! AppDelegate

    private lazy var rootAnimationOptions: UIWindow.TransitionOptions = {
        var options = UIWindow.TransitionOptions()
        options.direction = .fade
        options.duration = 0.15
        return options
    }()
    
    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplicationLaunchOptionsKey: Any]?) -> Bool {
        
        window?.tintColor = Colors.red
        
        ReachabilityManager.shared.startObserving()
        
        setCurrentLanguage()
        
        setupIQKeyBoardManager()
        
        setupSwipeToBack()
        
        setupNavigationBar()
        
        setRootController()
        
        GIDSignIn.sharedInstance().clientID = "5441228821-e78leulljlk09kut2vud907795d8lrh5.apps.googleusercontent.com"
        
        ApplicationDelegate.shared.application(application, didFinishLaunchingWithOptions: launchOptions)
        
        return true
    }
    
    private func setupNavigationBar() {
        UINavigationBar.appearance().tintColor = Colors.white
        UINavigationBar.appearance().barTintColor = Colors.red
        UINavigationBar.appearance().setBackgroundImage(UIImage(), for: .default)
        UINavigationBar.appearance().shadowImage = UIImage()
        UINavigationBar.appearance().isTranslucent = true
        
        UINavigationBar.appearance().titleTextAttributes = [
            .foregroundColor: Colors.white,
            .font: Fonts.centuryGothic(ofType: .bold, withSize: (isIpad ? 12 : 15))
        ]
    }
    
    func setRootController() {
        if isGuestUser {
            navigationToHome()
        } else if currentUser != nil {
            navigationToHome()
        } else {
            navigateToLogin()
        }
    }
    
    private func navigateToLogin() {
        let objlogin = UIStoryboard.auth.instantiateViewController(withClass: LoginVC.self)!
        let navigationController = NavigationController(rootViewController: objlogin)

        self.window?.setRootViewController(navigationController, options: rootAnimationOptions)
    }
    
    private func navigationToHome() {
        let objSelectLanguage = UIStoryboard.home.instantiateViewController(withClass: SupportLanguageVC.self)!
        let navigationController = NavigationController(rootViewController: objSelectLanguage)
        self.window?.setRootViewController(navigationController, options: rootAnimationOptions)
    }
    
    private func setCurrentLanguage() {
        UserDefaults.standard.set("en", forKey: "selectedLanguageCode")
        UserDefaults.standard.synchronize()
    }
    
    private func setupSwipeToBack() {
        NavigationController.disabledSwipeToBackControllers = [CongratsVC.self]
    }
    
    private func setupIQKeyBoardManager() {
        IQKeyboardManager.shared.enable = true
        IQKeyboardManager.shared.enableAutoToolbar = true
        IQKeyboardManager.shared.toolbarTintColor = Colors.red
        IQKeyboardManager.shared.keyboardDistanceFromTextField = 15
    }
    
    func application(_ application: UIApplication, open url: URL, sourceApplication: String?, annotation: Any) -> Bool {
        
        if url.absoluteString.contains("com.facebook") {
            return ApplicationDelegate.shared.application(application, open: url, sourceApplication: sourceApplication, annotation: annotation)
        }

        if url.absoluteString.contains("com.google") {
            GIDSignIn.sharedInstance().handle(url)
        }
        
        return false
    }

    func application(_ app: UIApplication, open url: URL, options: [UIApplicationOpenURLOptionsKey : Any] = [:]) -> Bool {
        if url.absoluteString.contains("com.facebook") {
            return ApplicationDelegate.shared.application(app, open: url, options: options)
        }
        
        if url.absoluteString.contains("com.google") {
            return GIDSignIn.sharedInstance().handle(url)
        }
        
        return false
    }
}

