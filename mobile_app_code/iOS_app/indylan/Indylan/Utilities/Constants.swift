//
//  Constants.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

// MARK: - App Constants -

var AppName             : String { infoDictionary["CFBundleName"] as! String }
var AppDisplayName      : String { infoDictionary["CFBundleDisplayName"] as! String }
var AppVersion          : String { infoDictionary["CFBundleShortVersionString"] as! String }
var BuildVersion        : String { infoDictionary["CFBundleVersion"] as! String }

var infoDictionary      : [String: Any] {
    Bundle.main.infoDictionary!
}

var BundleIdentifier    : String { Bundle.main.bundleIdentifier ?? "" }

let isIpad = UIDevice.current.userInterfaceIdiom == .pad

let UUID = UIDevice.current.identifierForVendor!.uuidString

let controllerTopCornerRadius: CGFloat = 35

// -----------------------------------------------------------------------------------------------

// MARK: - Screen (Width / Height / Ratio) -

var ScreenSize: CGSize {
    UIScreen.main.bounds.size
}

var ScreenWidth: CGFloat {
    ScreenSize.width
}
                                                                                                  
var ScreenHeight: CGFloat {
    ScreenSize.height
}

var AspectRatio: CGFloat {
    isIpad ? (ScreenHeight / 568) : (ScreenWidth / 320)
}

let topPadding: CGFloat = 8

let themeBorderWidth: CGFloat = 1.5

// -----------------------------------------------------------------------------------------------

var isGuestUser: Bool {
    UserDefaults.standard.value(forKey: "temp_user_id") != nil
}

var currentUser: CurrentUser? {
    if let userData = UserDefaults.standard.value(forKey: "kUserSession") as? [String: Any] {
        return CurrentUser(withJSON: JSON(userData))
    }
    return nil
}
extension Dictionary {
    func nullKeyRemoval() -> Dictionary {
        var dict = self
        
        let keysToRemove = Array(dict.keys).filter { dict[$0] is NSNull }
        for key in keysToRemove {
            dict.removeValue(forKey: key)
        }
        
        return dict
    }
}
//MARK:- IBInspectable
extension UIView {
    @IBInspectable var cornerNewRadius: CGFloat {
        get {
            return layer.cornerRadius
        }
        set {
            layer.cornerRadius = newValue
            layer.masksToBounds = newValue > 0
        }
    }

    @IBInspectable var borderNewWidth: CGFloat {
        get {
            return layer.borderWidth
        }
        set {
            layer.borderWidth = newValue
        }
    }

    @IBInspectable var borderNewColor: UIColor? {
        get {
            return UIColor(cgColor: layer.borderColor!)
        }
        set {
            layer.borderColor = newValue?.cgColor
        }
    }

    @IBInspectable
    var shadowNewRadius: CGFloat {
        get {
            return layer.shadowRadius
        }
        set {
            layer.masksToBounds = false
            layer.shadowRadius = newValue
        }
    }

    @IBInspectable
    var shadowNewOpacity: Float {
        get {
            return layer.shadowOpacity
        }
        set {
            layer.masksToBounds = false
            layer.shadowOpacity = newValue
        }
    }

    @IBInspectable
    var shadowNewOffset: CGSize {
        get {
            return layer.shadowOffset
        }
        set {
            layer.masksToBounds = false
            layer.shadowOffset = newValue
        }
    }

    @IBInspectable
    var shadowNewColor: UIColor? {
        get {
            if let color = layer.shadowColor {
                return UIColor(cgColor: color)
            }
            return nil
        }
        set {
            if let color = newValue {
                layer.shadowColor = color.cgColor
            } else {
                layer.shadowColor = nil
            }
        }
    }
}
