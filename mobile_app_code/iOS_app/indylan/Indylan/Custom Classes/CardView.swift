//
//  CardView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class CardView: UIView {
    
    private func setupView() {
        backgroundColor = Colors.box
        layer.borderColor = Colors.border.cgColor
        layer.borderWidth = themeBorderWidth
        layer.cornerRadius = 8
    }
    
    override func awakeFromNib() {
        super.awakeFromNib()
        setupView()
    }
    
}
