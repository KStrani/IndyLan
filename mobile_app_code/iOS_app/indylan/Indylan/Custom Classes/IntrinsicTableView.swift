//
//  IntrinsicTableView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class IntrinsicTableView: UITableView {
    
    // MARK: - Class Variables
    
    @IBInspectable var maxHeight: CGFloat = CGFloat.greatestFiniteMagnitude
    
    override var contentSize: CGSize {
        didSet {
            self.invalidateIntrinsicContentSize()
            self.isScrollEnabled = maxHeight < contentSize.height
        }
    }
    
    override var intrinsicContentSize: CGSize {
        layoutIfNeeded()
        let height = min(contentSize.height + contentInset.top + contentInset.bottom, maxHeight)
        return CGSize(width: contentSize.width, height: height)
    }
}
