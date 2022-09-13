//
//  BaseButton.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class BaseButton: UIButton {
    
    func setupView() {
        layer.cornerRadius = 8
    
        setTitleColor(Colors.white, for: .normal)
        setTitleColor(Colors.white, for: .disabled)
        
//        layer.shadowColor = Colors.gray.cgColor
//        layer.shadowOffset = CGSize(width: 0, height: 4)
//        layer.shadowRadius = 4
//        layer.shadowOpacity = 0.8
        
        titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
    }
    
    override func awakeFromNib() {
        super.awakeFromNib()
        setupView()
    }
    
}
