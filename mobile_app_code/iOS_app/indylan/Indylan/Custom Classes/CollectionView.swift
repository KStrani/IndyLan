//
//  CollectionView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 20/05/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

class CollectionView: UICollectionView {
    
    override func awakeFromNib() {
        super.awakeFromNib()
        clipsToBounds = true
        showsVerticalScrollIndicator = false
        showsHorizontalScrollIndicator = false
        contentInset = UIEdgeInsets(top: 15, left: 18, bottom: 0, right: 18)
    }
    
    override func layoutSubviews() {
        super.layoutSubviews()
        roundCorners(corners: [.topLeft, .topRight], radius: controllerTopCornerRadius)
    }
}
