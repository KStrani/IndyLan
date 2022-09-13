//
//  TapticEngine.swift
//  Indylan
//
//  Created by Bhavik Thummar on 24/04/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

final class TapticEngine {
    
    // MARK: - Class Properties
    
    static let impact: Impact = .init()
    static let selection: Selection = .init()
    static let notification: Notification = .init()
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Impact (UIImpactFeedbackGenerator)
    
    class Impact {
        
        // MARK: - Class Enums
        
        enum ImpactStyle {
            case light, medium, heavy
        }
        
        // -----------------------------------------------------------------------------------------------

        // MARK: - Class Properties
        
        private var style: ImpactStyle = .light
        private var generator: Any? = Impact.makeGenerator(.light)

        // -----------------------------------------------------------------------------------------------
        
        // MARK: - Class Functions
        
        func feedback(_ style: ImpactStyle) {
            guard #available(iOS 10.0, *) else { return }

            updateGeneratorIfNeeded(style)

            guard let generator = generator as? UIImpactFeedbackGenerator else { return }

            generator.impactOccurred()
            generator.prepare()
        }

        func prepare(_ style: ImpactStyle) {
            guard #available(iOS 10.0, *) else { return }

            updateGeneratorIfNeeded(style)

            guard let generator = generator as? UIImpactFeedbackGenerator else { return }

            generator.prepare()
        }
        
        private func updateGeneratorIfNeeded(_ style: ImpactStyle) {
            guard self.style != style else { return }

            generator = Impact.makeGenerator(style)
            self.style = style
        }
        
        // -----------------------------------------------------------------------------------------------
        
        // MARK: - Static Functions
        
        private static func makeGenerator(_ style: ImpactStyle) -> Any? {
            guard #available(iOS 10.0, *) else { return nil }

            let feedbackStyle: UIImpactFeedbackGenerator.FeedbackStyle
            switch style {
            case .light:
                feedbackStyle = .light
            case .medium:
                feedbackStyle = .medium
            case .heavy:
                feedbackStyle = .heavy
            }
            let generator: UIImpactFeedbackGenerator = UIImpactFeedbackGenerator(style: feedbackStyle)
            generator.prepare()
            return generator
        }

        // -----------------------------------------------------------------------------------------------
    }
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Selection (UISelectionFeedbackGenerator)

    class Selection {
        
        // MARK: - Class Properties
        
        private lazy var generator: Any? = {
            guard #available(iOS 10.0, *) else { return nil }

            let generator: UISelectionFeedbackGenerator = UISelectionFeedbackGenerator()
            generator.prepare()
            return generator
        }()
        
        // -----------------------------------------------------------------------------------------------
        
        // MARK: - Class Functions

        func feedback() {
            guard #available(iOS 10.0, *),
                let generator = generator as? UISelectionFeedbackGenerator else { return }

            generator.selectionChanged()
            generator.prepare()
        }

        func prepare() {
            guard #available(iOS 10.0, *),
                let generator = generator as? UISelectionFeedbackGenerator else { return }

            generator.prepare()
        }
        
        // -----------------------------------------------------------------------------------------------
    }
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Notification (UINotificationFeedbackGenerator)

    class Notification {

        // MARK: - Class Enums
        
        enum NotificationType {
            case success, warning, error
        }
        
        // -----------------------------------------------------------------------------------------------

        // MARK: - Class Properties
        
        private lazy var generator: Any? = {
            guard #available(iOS 10.0, *) else { return nil }

            let generator: UINotificationFeedbackGenerator = UINotificationFeedbackGenerator()
            generator.prepare()
            return generator
        }()
        
        // -----------------------------------------------------------------------------------------------

        // MARK: - Class Functions
        
        func feedback(_ type: NotificationType) {
            guard #available(iOS 10.0, *),
                let generator = generator as? UINotificationFeedbackGenerator else { return }

            let feedbackType: UINotificationFeedbackGenerator.FeedbackType
            switch type {
            case .success:
                feedbackType = .success
            case .warning:
                feedbackType = .warning
            case .error:
                feedbackType = .error
            }
            generator.notificationOccurred(feedbackType)
            generator.prepare()
        }

        func prepare() {
            guard #available(iOS 10.0, *),
                let generator = generator as? UINotificationFeedbackGenerator else { return }

            generator.prepare()
        }
        
        // -----------------------------------------------------------------------------------------------
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Initializers
    
    private init() {}
    
    // -----------------------------------------------------------------------------------------------
}
