//
//  ReachabilityManager.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import Foundation

// MARK: - Reachability Manager -

final class ReachabilityManager: NSObject {
    
    // MARK: - Shared
    
    static let shared: ReachabilityManager = ReachabilityManager()
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Properties
    
    private var reachability: Reachability!
    
    var isReachable: Bool {
        reachability.connection != .unavailable
    }
    
    var isReachableViaWWAN: Bool {
        reachability.connection == .cellular
    }
    
    var isReachableViaWiFi: Bool {
        reachability.connection == .wifi
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Functions
    
    func startObserving() {
        NotificationCenter.default.addObserver(self, selector: #selector(self.reachabilityChanged), name: NSNotification.Name.reachabilityChanged, object: nil)
        do {
            try self.reachability.startNotifier()
        }
        catch(let error) {
            Log.error("Error occured while starting reachability notifications : \(error.localizedDescription)")
        }
    }
    
    func stopObserving() {
        reachability.stopNotifier()
    }
    
    @objc private func reachabilityChanged(notification: NSNotification) {
        
    }
    
    // -----------------------------------------------------------------------------------------------
  
    // MARK: - Initializers
    
    private override init() {
        super.init()
        self.reachability = try! Reachability()
    }
    
    // -----------------------------------------------------------------------------------------------
    
}

// -----------------------------------------------------------------------------------------------


