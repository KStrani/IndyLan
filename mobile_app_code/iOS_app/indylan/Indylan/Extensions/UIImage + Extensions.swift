//
//  UIImage + Extensions.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

extension UIImage {
    
    func isEqualToImage(image: UIImage) -> Bool {
        let data1: NSData = UIImagePNGRepresentation(self)! as NSData
        let data2: NSData = UIImagePNGRepresentation(image)! as NSData
        return data1.isEqual(data2)
    }
    
    func withSize(_ targetSize: CGSize) -> UIImage {
        let imageSize = size
        
        let widthRatio  = targetSize.width  / imageSize.width
        let heightRatio = targetSize.height / imageSize.height
        
        var newSize: CGSize
        
        if widthRatio > heightRatio {
            newSize = CGSize(width: imageSize.width * heightRatio, height: imageSize.height * heightRatio)
        } else {
            newSize = CGSize(width: imageSize.width * widthRatio,  height: imageSize.height * widthRatio)
        }
        
        let rect = CGRect(x: 0, y: 0, width: newSize.width, height: newSize.height)
        
        UIGraphicsBeginImageContextWithOptions(newSize, false, 1.0)
        draw(in: rect)
        let newImage = UIGraphicsGetImageFromCurrentImageContext()
        UIGraphicsEndImageContext()
        
        return newImage ?? self
    }
    
    func hexagonImageWithBorder(width: CGFloat, color: UIColor) -> UIImage {
        let square = CGSize(width: min(size.width, size.height), height: min(size.width, size.height))
        let imageView = UIImageView(frame: CGRect(origin: CGPoint.zero, size: square))
        imageView.setupPolyagonView(lineWidth: width, lineColor: Colors.white, shape: .hexagon, cornerRadius: 5)
        imageView.contentMode = .scaleAspectFill
        imageView.image = self
        imageView.layer.masksToBounds = true
        UIGraphicsBeginImageContextWithOptions(imageView.bounds.size, false, scale)
        guard let context = UIGraphicsGetCurrentContext() else { return self }
        imageView.layer.render(in: context)
        let result = UIGraphicsGetImageFromCurrentImageContext()
        UIGraphicsEndImageContext()
        return result ?? self
    }
    
    func cropsToSquare() -> UIImage {
        let refWidth = CGFloat((self.cgImage!.width))
        let refHeight = CGFloat((self.cgImage!.height))
        let cropSize = refWidth > refHeight ? refHeight : refWidth
        
        let x = (refWidth - cropSize) / 2.0
        let y = (refHeight - cropSize) / 2.0
        
        let cropRect = CGRect(x: x, y: y, width: cropSize, height: cropSize)
        let imageRef = self.cgImage?.cropping(to: cropRect)
        let cropped = UIImage(cgImage: imageRef!, scale: 0.0, orientation: self.imageOrientation)
        
        return cropped
    }
}
