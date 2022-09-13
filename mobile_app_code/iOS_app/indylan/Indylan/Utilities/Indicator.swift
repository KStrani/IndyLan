//
//  Indicator.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

// MARK: - Indicator -

final class Indicator {
    
    // MARK: - Class Properties
    
    public static var heightWidth: CGFloat = 50
    
    public static var animationDuration: TimeInterval = 0.4
    
    public static var isAnimationg: Bool {
        activityIndicatorView.isAnimating
    }
    
    private static var ratio: CGFloat {
        heightWidth * AspectRatio
    }
    
    private static let containerView: UIView = {
        let view = UIView(frame: UIScreen.main.bounds)
        view.backgroundColor = Colors.black.withAlphaComponent(0.5)
        view.translatesAutoresizingMaskIntoConstraints = false
        view.addSubview(svBaseContainer)
        
        NSLayoutConstraint.activate([
            svBaseContainer.centerXAnchor.constraint(equalTo: view.centerXAnchor),
            svBaseContainer.centerYAnchor.constraint(equalTo: view.centerYAnchor),
            svBaseContainer.widthAnchor.constraint(equalTo: view.widthAnchor, constant: -30),
        ])
        
        return view
    }()
    
    private static var svBaseContainer: UIStackView = {
        let stackView = UIStackView()
        stackView.axis = .vertical
        stackView.distribution = .fill
        stackView.spacing = 2
        stackView.translatesAutoresizingMaskIntoConstraints = false
        stackView.addArrangedSubview(activityIndicatorView)
        stackView.addArrangedSubview(messageLabel)
        
        return stackView
    }()
    
    private static var activityIndicatorView: NVActivityIndicatorView = {
        let indicatorView = NVActivityIndicatorView(frame: CGRect(x: 0, y: 0, width: ratio, height: ratio), color: .white, padding: 0)
        return indicatorView
    }()
    
    private static let messageLabel: UILabel = {
        let label = UILabel()
        label.textAlignment = .center
        label.numberOfLines = 0
        label.translatesAutoresizingMaskIntoConstraints = false
        label.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
        label.textColor = Colors.white
        return label
    }()
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Static Functions
    
    static func show(withText text: String? = nil) {
        guard !isAnimationg, let window = AppDelegate.shared.window else { return }
        
        activityIndicatorView.startAnimating()
        
        window.addSubview(containerView)
        
        NSLayoutConstraint.activate([
            containerView.topAnchor.constraint(equalTo: window.topAnchor),
            containerView.bottomAnchor.constraint(equalTo: window.bottomAnchor),
            containerView.leadingAnchor.constraint(equalTo: window.leadingAnchor),
            containerView.trailingAnchor.constraint(equalTo: window.trailingAnchor),
        ])
        
        containerView.alpha = 0
        containerView.transform = CGAffineTransform(scaleX: 2, y: 2)
        
        messageLabel.text = text

        UIView.animate(withDuration: animationDuration) {
            containerView.alpha = 1.0
            containerView.transform = .identity
        }
    }
    
    static func hide() {
        guard isAnimationg else { return }
        
        UIView.animate(withDuration: animationDuration, animations: {
            containerView.transform = CGAffineTransform(scaleX: 2, y: 2)
            containerView.alpha = 0
        }) { _ in
            containerView.removeFromSuperview()
            activityIndicatorView.stopAnimating()
        }
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Initializers
    
    private init() {}
    
    // -----------------------------------------------------------------------------------------------
}

// -----------------------------------------------------------------------------------------------

