//
//  Fonts.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

// MARK: - App Fonts -

struct Fonts {
    
    // MARK: - Enums
    
    enum CenturyGothic: String {
        case regular    = "CenturyGothic-Regular"
        case italic     = "CenturyGothic-Italic"
        case bold       = "CenturyGothic-Bold"
        case boldItalic = "CenturyGothic-BoldItalic"
    }
    
    enum PoiretOne: String {
        case regular    = "PoiretOne"
    }
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Static Functions
    
    static func centuryGothic(ofType type: CenturyGothic, withSize size: CGFloat, isAspectRasio: Bool = true) -> UIFont {
        let finalSize = isAspectRasio ? size * AspectRatio : size
        return UIFont(name: type.rawValue, size: finalSize) ?? UIFont.systemFont(ofSize: finalSize)
    }
    
    static func poiretOne(ofType type: PoiretOne, withSize size: CGFloat, isAspectRasio: Bool = true) -> UIFont {
        let finalSize = isAspectRasio ? size * AspectRatio : size
        return UIFont(name: type.rawValue, size: finalSize) ?? UIFont.systemFont(ofSize: finalSize)
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Initializers
    
    private init() {}
    
    // -----------------------------------------------------------------------------------------------
    
}

// -----------------------------------------------------------------------------------------------

