//
//  CellExType1.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class CellExType1: UITableViewCell {

    @IBOutlet weak var optionView: OptionView!
    
    @IBOutlet weak var leadingConstraint: NSLayoutConstraint!
    @IBOutlet weak var trailingConstraint: NSLayoutConstraint!
    
    @IBOutlet weak var lblOption: UILabel!
    
    override func awakeFromNib() {
        selectionStyle = .none
        
        super.awakeFromNib()
        lblOption.textColor = Colors.black
        lblOption.font = Fonts.centuryGothic(ofType: .regular, withSize: 14)
    }
}
