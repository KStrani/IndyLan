//
//  OptionView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 12/05/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

class OptionView: CardView {
    
    enum State {
        
        struct Color {
            let backgroundColor: UIColor
            let borderColor: UIColor
        }
        
        case blue
        case white
        case green
        case normal
        case red
        
        var color: Color {
            switch self {
            case .blue:
                return Color(backgroundColor: Colors.blue, borderColor: Colors.blue)
            case .white:
                return Color(backgroundColor: Colors.white, borderColor: Colors.red)
            case .green:
                return Color(backgroundColor: Colors.green, borderColor: Colors.green)
            case .normal:
                return Color(backgroundColor: Colors.white, borderColor: Colors.red)
            case .red:
                return Color(backgroundColor: Colors.red, borderColor: Colors.red)
            }
        }
    }
    
    var state: State! {
        didSet {
            backgroundColor = state.color.backgroundColor.withAlphaComponent(0.15)
            layer.borderColor = state.color.borderColor.cgColor
        }
    }
    
    override func awakeFromNib() {
        super.awakeFromNib()
        layer.borderWidth = themeBorderWidth
    }
    
}
