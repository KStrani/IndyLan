//
//  ProfileImageView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 20/08/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

class ProfileImageView: TappableImageView {
    
    private func configure() {
        setupPolyagonView(lineWidth: 2, lineColor: Colors.border, shape: .hexagon, cornerRadius: 10)
    }
    
    override init(frame: CGRect) {
        super.init(frame: frame)
        configure()
    }
    
    required init?(coder: NSCoder) {
        super.init(coder: coder)
        configure()
    }
}
