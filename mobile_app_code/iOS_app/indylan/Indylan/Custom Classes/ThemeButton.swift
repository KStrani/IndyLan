//
//  ThemeButton.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class ThemeButton: BaseButton {
    
    override var isEnabled: Bool {
        didSet {
            backgroundColor = isEnabled ? Colors.red : Colors.gray
        }
    }
    
    override func setupView() {
        super.setupView()
        backgroundColor = Colors.red
    }
    
}
