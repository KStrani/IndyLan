//
//  UIImageView + Extensions.swift
//  Stickerry
//
//  Created by Bhavik Thummar on 08/04/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import SDWebImage

// MARK: - Set Image From URL -

extension UIImageView {
    
    func setImage(withURL url: String, AndPlaceholder placeholder: UIImage? = nil, completion: SDExternalCompletionBlock? = nil) {
        guard let imageURL = URL(string: url) else { return }
        sd_imageTransition = .fade
        sd_imageIndicator = SDWebImageActivityIndicator.medium
        sd_setImage(with: imageURL, placeholderImage: placeholder, options: [.retryFailed]) { (image, error, cache, url) in
            completion?(image, error, cache, url)
        }
    }
}
