//
//  SocialButton.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class SocialButton: BaseButton {
    
    enum SocialType {
        case facebook, google
    }
    
    var type: SocialType! {
        didSet {
            switch type {
            case .facebook:
                backgroundColor = Colors.facebook
                setImage(UIImage(named: "facebook"), for: .normal)
            case .google:
                backgroundColor = Colors.google
                setImage(UIImage(named: "google"), for: .normal)
            default:
                break
            }
        }
    }
    
    override func setupView() {
        super.setupView()
        contentHorizontalAlignment = .left
        contentEdgeInsets = UIEdgeInsets(top: 0, left: 40, bottom: 0, right: 20)
        imageEdgeInsets = UIEdgeInsets(top: 0, left: -20, bottom: 0, right: 0)
        
        setTitleColor(Colors.white, for: .normal)
    }
}
