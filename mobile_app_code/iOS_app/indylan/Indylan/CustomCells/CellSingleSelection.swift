//
//  CellSingleSelection.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class CellSingleSelection: UITableViewCell {
    
    @IBOutlet var viewCellBg: CardView!
    
    @IBOutlet var imgView: UIImageView!
 
    @IBOutlet var lblTitle: UILabel!
    
    @IBOutlet var viewStar: CosmosView!
    
    
    override func awakeFromNib() {
        super.awakeFromNib()
        selectionStyle = .none
        imgView.backgroundColor = Colors.white
        imgView.setupPolyagonView(lineWidth: 3, lineColor: Colors.box, shape: .hexagon, cornerRadius: 4)
        
        viewStar.isHidden = true
        
        lblTitle.textColor = Colors.black
        lblTitle.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
    }
}
