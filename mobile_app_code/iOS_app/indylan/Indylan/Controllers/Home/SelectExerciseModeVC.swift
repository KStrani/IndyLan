//
//  SelectExerciseModeVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

var selectedExerciseId = ""
var selectedExercise = ""

struct Exercise {
    let id          : String
    let modelName   : String
    
    var image: UIImage? {
        UIImage(named: modelName)
    }
}

class SelectExerciseModeVC: ThemeViewController, UITableViewDelegate, UITableViewDataSource {
    
    //MARK: DECLARATION
    
    @IBOutlet var tblViewSelectExercise: UITableView!
    
    let arrExercises: [Exercise] = [
        Exercise(id: "1", modelName: "vocabulary"),
        Exercise(id: "2", modelName: "dialogues"),
        Exercise(id: "3", modelName: "phrases"),
        Exercise(id: "4", modelName: "grammar"),
        Exercise(id: "5", modelName: "cultureHistory"),
        Exercise(id: "6", modelName: "Aural Comprehension"),
    ]
    override func viewDidLoad() {
        super.viewDidLoad()

        self.title = "selectExercisemode".localized()

        tblViewSelectExercise.register(UINib(nibName: "ListCell", bundle: nil), forCellReuseIdentifier: "ListCell")
    }

    //MARK: UITABLEVIEW DELEGATE METHODS
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrExercises.count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cellSelection = tblViewSelectExercise.dequeueReusableCell(withIdentifier: "ListCell") as! ListCell
        
        let image = arrExercises[indexPath.row].image
        cellSelection.imgIcon.isHidden = (image == nil)
        cellSelection.imgIcon.image = image
        
//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27"
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.right
//        }
//        else
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.left
//        }
        
        cellSelection.lblTitle.text = arrExercises[indexPath.row].modelName.localized()
        
        return cellSelection
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        selectedExerciseId = arrExercises[indexPath.row].id
        selectedExercise = arrExercises[indexPath.row].modelName.localized()

        TapticEngine.impact.feedback(.light)
        
        navigateToNextView()
    }
    
//MARK: BUTTON ACTIONS
    
    func navigateToNextView() {
        
        if selectedExerciseId == "1111"
        {
            selectedCategory = "test".localized()
            
            let objTest = UIStoryboard.home.instantiateViewController(withClass: TestVC.self)!
            self.navigationController?.pushViewController(objTest, animated: true)
            return
        }
        
        if ReachabilityManager.shared.isReachable
        {
            let dictParam = [
                "exercise_mode_id"  : selectedExerciseId
            ]
            
            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/get_category_list") as String, params: dictParam, completion:
            { (status, resObj) in
                
                if status == true
                {
                    if (resObj["status"].intValue) == 1
                    {
                        let objChooseCategory = UIStoryboard.home.instantiateViewController(withClass: ChooseCategoryVC.self)!
                        objChooseCategory.arrCategory = resObj["result"].arrayValue
                        self.navigationController?.pushViewController(objChooseCategory, animated: true)
                    }
                    else
                    {
                        SnackBar.show("\(resObj["message"])")
                    }
                }
                else
                {
                    if resObj["message"].stringValue.count > 0
                    {
                        SnackBar.show("\(resObj["message"])")
                    }
                    else
                    {
                        SnackBar.show("serverTimeout".localized())
                    }
                }
            })
        }
        else
        {
            SnackBar.show("noInternet".localized())
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
