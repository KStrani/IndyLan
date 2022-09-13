//
//  BaseScrollView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 12/05/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

class BaseScrollView: UIScrollView {
    
    override func awakeFromNib() {
        super.awakeFromNib()
        showsVerticalScrollIndicator = false
        showsHorizontalScrollIndicator = false
        clipsToBounds = true
        contentInset = UIEdgeInsets(top: 0, left: 0, bottom: ScreenHeight * 0.12, right: 0)
    }
    
    override func layoutSubviews() {
        super.layoutSubviews()
        roundCorners(corners: [.topLeft, .topRight], radius: controllerTopCornerRadius)
    }
}
