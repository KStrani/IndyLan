//
//  ExerciseController.swift
//  Indylan
//
//  Created by Bhavik Thummar on 12/05/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

class ExerciseController: ThemeViewController {
    
    override func backButtonAction() {
        let viewControllers = self.navigationController!.viewControllers
        
        for aViewController in viewControllers {
            if(aViewController is ChooseExTypeVC) {
                self.navigationController?.popToViewController(aViewController, animated: true)
                return
            }
        }
        
        self.navigationController?.popViewController(animated: true)
    }
}
