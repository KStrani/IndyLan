//
//  PickerTextField.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class PickerTextField: ThemeTextField {
    
    override var isEnabled: Bool {
        didSet {
            layer.borderColor = isEnabled ? Colors.red.cgColor : Colors.border.cgColor
        }
    }
    
    override func setupView() {
        super.setupView()
        padding = UIEdgeInsets.zero
        textAlignment = .center
        canPerformActions = false
        tintColor = .clear
        layer.borderColor = Colors.red.cgColor
    }
}
