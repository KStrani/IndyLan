//
//  UIView + Extensions.swift
//  Indylan
//
//  Created by Bhavik Thummar on 09/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

extension UIView {
    
    enum Shape {
        case triangle
        case square
        case pentagon
        case hexagon
        case polygon(Int)
        
        var side: Int {
            switch self {
            case .triangle:
                return 3
            case .square:
                return 4
            case .pentagon:
                return 5
            case .hexagon:
                return 6
            case .polygon(let value):
                // octagon, nonagon, decagon...
                return value
            }
        }
    }
    
    func setupPolyagonView(lineWidth: CGFloat = 5, lineColor: UIColor = .white, shape: Shape = .hexagon, cornerRadius: CGFloat = 0, rotationOffset: CGFloat = .pi / 2.0) {
        let path = roundedPolygonPath(rect: bounds, lineWidth: lineWidth, sides: shape.side, cornerRadius: cornerRadius, rotationOffset: rotationOffset)

        let mask = CAShapeLayer()
        mask.path = path.cgPath
        mask.lineWidth = lineWidth
        mask.strokeColor = UIColor.clear.cgColor
        mask.fillColor = UIColor.white.cgColor
        layer.mask = mask

        let border = CAShapeLayer()
        border.path = path.cgPath
        border.lineWidth = lineWidth
        border.strokeColor = lineColor.cgColor
        border.fillColor = UIColor.clear.cgColor
        layer.addSublayer(border)
    }
    
    private func roundedPolygonPath(rect: CGRect, lineWidth: CGFloat, sides: NSInteger, cornerRadius: CGFloat, rotationOffset: CGFloat = 0) -> UIBezierPath {
        let path = UIBezierPath()
        let theta: CGFloat = CGFloat(2.0 * .pi) / CGFloat(sides) // How much to turn at every corner
        let width = min(rect.size.width, rect.size.height)        // Width of the square

        let center = CGPoint(x: rect.origin.x + width / 2.0, y: rect.origin.y + width / 2.0)

        // Radius of the circle that encircles the polygon
        // Notice that the radius is adjusted for the corners, that way the largest outer
        // dimension of the resulting shape is always exactly the width - linewidth
        let radius = (width - lineWidth + cornerRadius - (cos(theta) * cornerRadius)) / 2.0

        // Start drawing at a point, which by default is at the right hand edge
        // but can be offset
        var angle = CGFloat(rotationOffset)

        let corner = CGPoint(x: center.x + (radius - cornerRadius) * cos(angle), y: center.y + (radius - cornerRadius) * sin(angle))
        path.move(to: CGPoint(x: corner.x + cornerRadius * cos(angle + theta), y: corner.y + cornerRadius * sin(angle + theta)))

        for _ in 0 ..< sides {
            angle += theta

            let corner = CGPoint(x: center.x + (radius - cornerRadius) * cos(angle), y: center.y + (radius - cornerRadius) * sin(angle))
            let tip = CGPoint(x: center.x + radius * cos(angle), y: center.y + radius * sin(angle))
            let start = CGPoint(x: corner.x + cornerRadius * cos(angle - theta), y: corner.y + cornerRadius * sin(angle - theta))
            let end = CGPoint(x: corner.x + cornerRadius * cos(angle + theta), y: corner.y + cornerRadius * sin(angle + theta))

            path.addLine(to: start)
            path.addQuadCurve(to: end, controlPoint: tip)
        }

        path.close()

        // Move the path to the correct origins
        let bounds = path.bounds
//        let transform = CGAffineTransform(translationX: -bounds.origin.x + rect.origin.x + lineWidth / 2.0,
//                                          y: -bounds.origin.y + rect.origin.y + lineWidth / 2.0)
        
        let transform = CGAffineTransform(translationX: 0,
                                          y: -bounds.origin.y + rect.origin.y + lineWidth / 2.0)
        
        path.apply(transform)

        return path
    }
    
    func screenshot() -> UIImage? {
//        UIGraphicsBeginImageContextWithOptions(self.bounds.size, false, UIScreen.main.scale)
//        drawHierarchy(in: self.bounds, afterScreenUpdates: true)
//        let screenshot = UIGraphicsGetImageFromCurrentImageContext()
//        UIGraphicsEndImageContext()
//        return screenshot
        
        if #available(iOS 10.0, *) {
            let renderer = UIGraphicsImageRenderer(size: bounds.size)
            let image = renderer.image { ctx in
                drawHierarchy(in: bounds, afterScreenUpdates: true)
            }
            return image
        } else {
            return nil
        }
    }
}

extension UIView {
    
    func roundCorners(corners: UIRectCorner, radius: CGFloat) {
        let path = UIBezierPath(roundedRect: bounds, byRoundingCorners: corners, cornerRadii: CGSize(width: radius, height: radius))
        let mask = CAShapeLayer()
        mask.path = path.cgPath
        layer.mask = mask
    }
    
    func shake(pixel: CGFloat = 10.0) {
        let animation = CABasicAnimation(keyPath: "position")
        animation.duration = 0.07
        animation.repeatCount = 3
        animation.autoreverses = true
        animation.fromValue = NSValue(cgPoint: CGPoint(x: self.center.x - pixel, y: self.center.y))
        animation.toValue = NSValue(cgPoint: CGPoint(x: self.center.x + pixel, y: self.center.y))
        self.layer.add(animation, forKey: "position")
    }
    
    func animateBorderColor(toColor: UIColor, duration: Double) {
        let animation:CABasicAnimation = CABasicAnimation(keyPath: "borderColor")
        animation.fromValue = layer.borderColor
        animation.toValue = toColor.cgColor
        animation.duration = duration
        layer.add(animation, forKey: "borderColor")
        layer.borderColor = toColor.cgColor
    }
}

extension UIView {
    
    fileprivate struct AssociatedObjectKeys {
        static var tapGestureRecognizer = "MediaViewerAssociatedObjectKey_mediaViewer"
    }
    
    fileprivate typealias Action = (() -> Void)?
    
    fileprivate var tapGestureRecognizerAction: Action? {
        set {
            if let newValue = newValue {
                // Computed properties get stored as associated objects
                objc_setAssociatedObject(self, &AssociatedObjectKeys.tapGestureRecognizer, newValue, objc_AssociationPolicy.OBJC_ASSOCIATION_RETAIN)
            }
        }
        get {
            let tapGestureRecognizerActionInstance = objc_getAssociatedObject(self, &AssociatedObjectKeys.tapGestureRecognizer) as? Action
            return tapGestureRecognizerActionInstance
        }
    }
    
    public func addTapGestureRecognizer(action: (() -> Void)?) {
        self.isUserInteractionEnabled = true
        self.tapGestureRecognizerAction = action
        let tapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(handleTapGesture))
        self.addGestureRecognizer(tapGestureRecognizer)
    }
    
    @objc fileprivate func handleTapGesture(sender: UITapGestureRecognizer) {
        if let action = self.tapGestureRecognizerAction {
            action?()
        }
    }
}
