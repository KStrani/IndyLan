//
//  DynamicCollectionView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

// MARK: - Dynamic CollectionView -

class DynamicCollectionView: UICollectionView {
    
    // MARK: - Override Properties
    
    override var intrinsicContentSize: CGSize {
        self.contentSize
    }
    
    // MARK: - Life Cycle Functions
    
    override func layoutSubviews() {
        super.layoutSubviews()
        if bounds.size != intrinsicContentSize {
            invalidateIntrinsicContentSize()
        }
    }
}
