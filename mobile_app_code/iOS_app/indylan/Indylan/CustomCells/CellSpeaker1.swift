//
//  CellSpeaker1.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class CellSpeaker1: UITableViewCell {

    @IBOutlet var contentView1: CardView!
    
    @IBOutlet var btnAudio1: UIButton!
    
    @IBOutlet var lblSpeaker1: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        selectionStyle = .none
        
        contentView1.backgroundColor = Colors.blue.withAlphaComponent(0.15)
        contentView1.layer.borderWidth = themeBorderWidth
        contentView1.layer.borderColor = Colors.blue.cgColor
        
        lblSpeaker1.textColor = Colors.black
        lblSpeaker1.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }
    
}
