//
//  ListCell.swift
//  Indylan
//
//  Created by Bhavik Thummar on 20/08/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

class ListCell: UITableViewCell {

    @IBOutlet weak var viewCellBg: CardView!
    
    @IBOutlet weak var imgIcon: UIImageView!
    @IBOutlet weak var lblTitle: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        selectionStyle = .none
        
        lblTitle.textColor = Colors.black
        lblTitle.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
}
