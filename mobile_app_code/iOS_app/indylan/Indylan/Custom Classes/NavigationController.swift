//
//  NavigationController.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class NavigationController: UINavigationController {
    
    // MARK: - Class Properties
    
    static var disabledSwipeToBackControllers: [UIViewController.Type] = []
    
    fileprivate var duringPushAnimation = false
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Override Properties
    
    override var childViewControllerForStatusBarStyle: UIViewController? {
        topViewController
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Memory Management Functions
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    deinit {
        delegate = nil
        interactivePopGestureRecognizer?.delegate = nil
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Functions
    
    private func initialSetup() {
        delegate = self
        interactivePopGestureRecognizer?.delegate = self
        navigationItem.backBarButtonItem?.isEnabled = true
        interactivePopGestureRecognizer?.isEnabled = true
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Lifecycle Functions
    
    override init(rootViewController: UIViewController) {
        super.init(rootViewController: rootViewController)
    }
    
    override init(nibName nibNameOrNil: String?, bundle nibBundleOrNil: Bundle?) {
        super.init(nibName: nibNameOrNil, bundle: nibBundleOrNil)
        self.initialSetup()
    }
    
    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        self.initialSetup()
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // This needs to be in here, not in init
        initialSetup()
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Overrides Functions
    
    override func pushViewController(_ viewController: UIViewController, animated: Bool) {
        duringPushAnimation = true
        super.pushViewController(viewController, animated: animated)
    }
}

// -----------------------------------------------------------------------------------------------

// MARK: - UINavigationController Delegate

extension NavigationController: UINavigationControllerDelegate {
    
    func navigationController(_ navigationController: UINavigationController, didShow viewController: UIViewController, animated: Bool) {
        
        guard let swipeNavigationController = navigationController as? NavigationController else {
            return
        }
        
        swipeNavigationController.duringPushAnimation = false
    }
    
}

// -----------------------------------------------------------------------------------------------

// MARK: - UIGestureRecognizer Delegate

extension NavigationController: UIGestureRecognizerDelegate {
    
    func gestureRecognizerShouldBegin(_ gestureRecognizer: UIGestureRecognizer) -> Bool {
        guard gestureRecognizer == interactivePopGestureRecognizer else {
            return true
        }
        
        // Disable pop gesture in two situations:
        // 1) when the pop animation is in progress
        // 2) when user swipes quickly a couple of times and animations don't have time to be performed

        if !NavigationController.disabledSwipeToBackControllers.isEmpty, let visibleViewController = self.visibleViewController {
            for disabledController in NavigationController.disabledSwipeToBackControllers {
                if visibleViewController.isKind(of: disabledController) {
                    return false
                }
            }
        }
        
        return viewControllers.count > 1 && !duringPushAnimation
    }
}

// -----------------------------------------------------------------------------------------------
