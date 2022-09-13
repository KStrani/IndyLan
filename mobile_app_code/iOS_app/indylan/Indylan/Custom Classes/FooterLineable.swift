//
//  FooterLineable.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

protocol FooterLineable {}

extension FooterLineable where Self: UIViewController {
    
    func addFooterView() {
        let imgBottom = UIImageView()
        imgBottom.image = #imageLiteral(resourceName: "footer")
        imgBottom.contentMode = .scaleToFill
        imgBottom.translatesAutoresizingMaskIntoConstraints = false
        view.addSubview(imgBottom)
        
        NSLayoutConstraint.activate([
            imgBottom.leadingAnchor.constraint(equalTo: view.leadingAnchor),
            imgBottom.trailingAnchor.constraint(equalTo: view.trailingAnchor),
            imgBottom.heightAnchor.constraint(equalToConstant: ScreenHeight * 0.12),
            imgBottom.bottomAnchor.constraint(equalTo: view.bottomAnchor),
        ])
    }
}
