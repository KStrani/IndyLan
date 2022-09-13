//
//  UIBarButtonItem.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

extension UIBarButtonItem {
    
    private struct AssociatedObject {
        static var key = "action_closure_key"
    }

    var actionClosure: (()->Void)? {
        get {
            return objc_getAssociatedObject(self, &AssociatedObject.key) as? ()->Void
        }
        set {
            objc_setAssociatedObject(self, &AssociatedObject.key, newValue, .OBJC_ASSOCIATION_RETAIN_NONATOMIC)
            target = self
            action = #selector(didTapButton(sender:))
        }
    }

    @objc func didTapButton(sender: Any) {
        actionClosure?()
    }
}

