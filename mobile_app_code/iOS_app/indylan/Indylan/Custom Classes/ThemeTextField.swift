//
//  ThemeTextField.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class ThemeTextField: UITextField {

    override var isEnabled: Bool {
        didSet {
            layer.borderColor = isEnabled ? Colors.border.cgColor : Colors.gray.cgColor
        }
    }
    
    var canPerformActions: Bool = true
    
    var padding = UIEdgeInsets(top: 0, left: 15, bottom: 0, right: 15)
    
    func setupView() {
        backgroundColor = Colors.box
        
        textColor = Colors.black
        
        layer.cornerRadius = 6.0
        layer.borderColor = Colors.border.cgColor
        layer.borderWidth = themeBorderWidth

        font = Fonts.centuryGothic(ofType: .bold, withSize: 16)
        
        setupPlaceHolder()
    }
    
    private func setupPlaceHolder() {
        var placeholderAttributes = [NSAttributedString.Key: AnyObject]()
        placeholderAttributes[NSAttributedString.Key.font] = Fonts.centuryGothic(ofType: .regular, withSize: 16)
        placeholderAttributes[NSAttributedString.Key.foregroundColor] = Colors.placeholder

        if let placeholder = placeholder {
            let newAttributedPlaceholder = NSAttributedString(string: placeholder, attributes: placeholderAttributes)
            attributedPlaceholder = newAttributedPlaceholder
        }
    }
    
    override func awakeFromNib() {
        super.awakeFromNib()
        setupView()
    }
    
    override func canPerformAction(_ action: Selector, withSender sender: Any?) -> Bool {
        if canPerformActions {
            return (action == #selector(UIResponderStandardEditActions.cut) || action == #selector(UIResponderStandardEditActions.copy) || action == #selector(UIResponderStandardEditActions.paste))
        } else {
            return false
        }
    }
    
    override func textRect(forBounds bounds: CGRect) -> CGRect {
        UIEdgeInsetsInsetRect(bounds, padding)
    }
    
    override func placeholderRect(forBounds bounds: CGRect) -> CGRect {
        UIEdgeInsetsInsetRect(bounds, padding)
    }
    
    override func editingRect(forBounds bounds: CGRect) -> CGRect {
        UIEdgeInsetsInsetRect(bounds, padding)
    }
}
