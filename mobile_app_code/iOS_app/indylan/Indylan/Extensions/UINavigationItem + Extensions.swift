//
//  UINavigationItem + Extensions.swift
//  Indylan
//
//  Created by Bhavik Thummar on 09/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

extension UINavigationItem {

    func setTitle(title:String, subtitle:String) {
        
        let lblTitle = UILabel()
        lblTitle.textColor = Colors.white
        lblTitle.text = title
        lblTitle.font = Fonts.centuryGothic(ofType: .bold, withSize: (isIpad ? 12 : 15))
        lblTitle.sizeToFit()
        
        let lblSubtitle = UILabel()
        lblSubtitle.textColor = Colors.white
        lblSubtitle.text = subtitle
        lblSubtitle.font = Fonts.centuryGothic(ofType: .bold, withSize: (isIpad ? 9 : 11))
        lblSubtitle.textAlignment = .center
        lblSubtitle.sizeToFit()
        
        let stackView = UIStackView(arrangedSubviews: [lblTitle, lblSubtitle])
        stackView.distribution = .equalCentering
        stackView.axis = .vertical
        stackView.alignment = .center
        stackView.spacing = 2
        
        let width = max(lblTitle.frame.size.width, lblSubtitle.frame.size.width)
        stackView.frame = CGRect(x: 0, y: 0, width: width, height: 35)
        
        lblTitle.sizeToFit()
        lblSubtitle.sizeToFit()
        
        self.titleView = stackView
    }
}
