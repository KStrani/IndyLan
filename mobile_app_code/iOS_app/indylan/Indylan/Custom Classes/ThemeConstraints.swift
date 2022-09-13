//
//  ThemeConstraints.swift
//  Indylan
//
//  Created by Bhavik Thummar on 15/05/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

class ThemeBottomConstraint: NSLayoutConstraint {
    
    override func awakeFromNib() {
        super.awakeFromNib()
        
        if self.firstAttribute == .bottom {
            constant += 0
        }
    }
}

class ThemeTopConstraint: NSLayoutConstraint {
    
    override func awakeFromNib() {
        super.awakeFromNib()
        
        if self.firstAttribute == .top {
            constant += topPadding
        }
    }
}
