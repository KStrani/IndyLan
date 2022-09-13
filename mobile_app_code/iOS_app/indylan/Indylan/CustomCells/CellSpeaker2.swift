//
//  CellSpeaker2.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class CellSpeaker2: UITableViewCell {

    @IBOutlet var contentView2: UIView!
    
    @IBOutlet var lblSpeaker2: UILabel!
    
    @IBOutlet var btnAudio2: UIButton!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        selectionStyle = .none
        
        contentView2.backgroundColor = Colors.green.withAlphaComponent(0.15)
        contentView2.layer.borderWidth = themeBorderWidth
        contentView2.layer.borderColor = Colors.green.cgColor
        
        lblSpeaker2.textColor = Colors.black
        lblSpeaker2.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }
    
}
