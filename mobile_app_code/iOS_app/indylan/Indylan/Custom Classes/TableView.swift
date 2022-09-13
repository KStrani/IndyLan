//
//  TableView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 09/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class TableView: UITableView {
    
    override func awakeFromNib() {
        super.awakeFromNib()
        clipsToBounds = true
        estimatedRowHeight = 70
        rowHeight = UITableViewAutomaticDimension
        showsVerticalScrollIndicator = false
        showsHorizontalScrollIndicator = false
        contentInset = UIEdgeInsets(top: 15, left: 0, bottom: ScreenHeight * 0.12, right: 0)
    }
    
    override func layoutSubviews() {
        super.layoutSubviews()
        roundCorners(corners: [.topLeft, .topRight], radius: controllerTopCornerRadius)
    }
}
