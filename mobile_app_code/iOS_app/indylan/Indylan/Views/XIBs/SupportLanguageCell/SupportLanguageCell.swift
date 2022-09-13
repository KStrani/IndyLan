//
//  SupportLanguageCell.swift
//  Indylan
//
//  Created by Bhavik Thummar on 20/05/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

class SupportLanguageCell: UICollectionViewCell {

    @IBOutlet weak var vwContainer: CardView!
    @IBOutlet weak var lblTitle: UILabel!
    
    var language: SupportLanguage! {
        didSet {
            lblTitle.text = language.name.capitalized
        }
    }
    
    override func awakeFromNib() {
        super.awakeFromNib()
        vwContainer.layer.borderColor = Colors.gray.cgColor
        vwContainer.layer.borderWidth = themeBorderWidth
        
        lblTitle.textColor = Colors.black
        lblTitle.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
    }

}
