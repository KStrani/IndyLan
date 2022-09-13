//
//  Bool + Extensions.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import Foundation

extension Bool {

    var intValue: Int {
        return self ? 1 : 0
    }
    
    mutating func toggle() {
        self = !self
    }
}
