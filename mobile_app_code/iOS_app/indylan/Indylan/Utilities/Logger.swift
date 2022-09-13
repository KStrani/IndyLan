//
//  Logger.swift
//  Stickerry
//
//  Created by Bhavik Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Bhavik Thummar. All rights reserved.
//

import Foundation

// MARK: - Log -

final class Log {
    
    // MARK: - Class Enums
    
    enum LogEvent: String {
        case error      = "[‼️]"
        case info       = "[ℹ️]"
        case debug      = "[💬]"
        case verbose    = "[🔬]"
        case warning    = "[⚠️]"
        case server     = "[🔥]"
        case unknown    = "[➖]"
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Properties
    
    static var dateFormat = "hh:mm:ss"
    
    static var dateFormatter: DateFormatter {
        let formatter = DateFormatter()
        formatter.dateFormat = dateFormat
        formatter.locale = Locale.current
        formatter.timeZone = TimeZone.current
        return formatter
    }
    
    private static var isLoggingEnabled: Bool {
        #if DEBUG
        return true
        #else
        return false
        #endif
    }
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Static Functions
   
    static func error( _ object: Any, filename: String = #file, line: Int = #line, funcName: String = #function) {
        logPrint(object, eventType: .error, filename: filename, line: line, funcName: funcName)
    }

    static func info( _ object: Any, filename: String = #file, line: Int = #line, funcName: String = #function) {
        logPrint(object, eventType: .info, filename: filename, line: line, funcName: funcName)
    }
 
    static func debug( _ object: Any, filename: String = #file, line: Int = #line, funcName: String = #function) {
        logPrint(object, eventType: .debug, filename: filename, line: line, funcName: funcName)
    }

    static func verbose( _ object: Any, filename: String = #file, line: Int = #line, funcName: String = #function) {
        logPrint(object, eventType: .verbose, filename: filename, line: line, funcName: funcName)
    }

    static func warning( _ object: Any, filename: String = #file, line: Int = #line, funcName: String = #function) {
        logPrint(object, eventType: .warning, filename: filename, line: line, funcName: funcName)
    }
  
    static func server( _ object: Any, filename: String = #file, line: Int = #line, funcName: String = #function) {
        logPrint(object, eventType: .server, filename: filename, line: line, funcName: funcName)
    }

    static func shortLine(filename: String = #file, line: Int = #line, funcName: String = #function) {
        let lineString = "-----------------------------------------------------------------------------------------------"
        logPrint(lineString, eventType: .unknown, filename: filename, line: line, funcName: funcName)
    }
    
    private static func logPrint( _ object: Any, eventType: LogEvent, filename: String = #file, line: Int = #line, funcName: String = #function) {
        if isLoggingEnabled {
            if eventType == .unknown {
                print("\(object)")
            } else {
                print("\(Date().toString()) \(eventType.rawValue) [\(sourceFileName(filePath: filename))]: \(line) -> \(object)")
            }
        }
    }
    
    private static func sourceFileName(filePath: String) -> String {
        let components = filePath.components(separatedBy: "/")
        return components.isEmpty ? "" : components.last!
    }
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Initializers
    
    private init() {}
    
    // -----------------------------------------------------------------------------------------------
}

// -----------------------------------------------------------------------------------------------

// MARK: - Print Function -

func print(_ object: Any) {
    // Only allowing in DEBUG mode
    #if DEBUG
    Swift.print(object)
    #endif
}

// -----------------------------------------------------------------------------------------------

// MARK: - Date to String -

fileprivate extension Date {
    func toString() -> String {
        Log.dateFormatter.string(from: self)
    }
}

// -----------------------------------------------------------------------------------------------


