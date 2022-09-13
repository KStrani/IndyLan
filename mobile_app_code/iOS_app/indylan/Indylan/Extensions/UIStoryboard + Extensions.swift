//
//  UIStoryboard + Extensions.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

// MARK: - Storyboards -

extension UIStoryboard {
    
    // MARK: - Class Properties
    
    private static var bundle: Bundle {
        Bundle.main
    }

    static var auth: UIStoryboard {
        UIStoryboard(name: "Auth", bundle: bundle)
    }
    
    static var home: UIStoryboard {
        UIStoryboard(name: "Home", bundle: bundle)
    }
    
    static var exercise: UIStoryboard {
        UIStoryboard(name: "Exercise", bundle: bundle)
    }
    
    func instantiateViewController<T: UIViewController>(withClass name: T.Type) -> T? {
        instantiateViewController(withIdentifier: String(describing: name)) as? T
    }
    
    // -----------------------------------------------------------------------------------------------
        
}

// -----------------------------------------------------------------------------------------------

