//
//  ChooseCategoryVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

var selectedCategoryId = ""
var selectedCategory = ""

class ChooseCategoryVC: ThemeViewController, UITableViewDataSource, UITableViewDelegate {
    
    //MARK: DECLARATION
    
    @IBOutlet var tblViewChooseCategory: TableView!
    
    var arrCategory = Array<JSON>()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        navigationItem.setTitle(title: "chooseCategory".localized(), subtitle: selectedExercise.uppercased())

        tblViewChooseCategory.register(UINib(nibName: "CellSingleSelection", bundle: nil), forCellReuseIdentifier: "cellSelection")
    }
    
    //MARK: UITABLEVIEW DELEGATE METHODS

    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrCategory.count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cellSelection = tblViewChooseCategory.dequeueReusableCell(withIdentifier: "cellSelection") as! CellSingleSelection
        
//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27"
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.right
//        }
//        else
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.left
//        }
        
        cellSelection.lblTitle.text = arrCategory[indexPath.row]["category_name"].stringValue
        
        let imageURL = arrCategory[indexPath.row]["image_path"].stringValue
        cellSelection.imgView.isHidden = imageURL.isEmpty
        cellSelection.imgView.setImage(withURL: imageURL)
        
        return cellSelection
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        selectedCategoryId = arrCategory[indexPath.row]["exercise_mode_category_id"].stringValue
        selectedCategory = arrCategory[indexPath.row]["category_name"].stringValue
        
        TapticEngine.impact.feedback(.light)
        
        navigateToNextView()
    }
    
    //MARK: BUTTON ACTIONS
    
    func navigateToNextView() {
        
        if ReachabilityManager.shared.isReachable {
            
            var userId: String = "0"
            
            if let user = currentUser {
                userId = user.userId
            }
            
            let dictParam = [
                "user_id"       : userId,
                "category_id"   : selectedCategoryId,
            ]

            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/get_subcategory_list") as String, params: dictParam, completion:
                { (status, resObj) in
                    
                    if status == true
                    {   
                        if (resObj["status"].intValue) == 1
                        {
                            if resObj["result"].count > 0
                            {
                                let objChooseSubCategory = UIStoryboard.home.instantiateViewController(withClass: ChooseSubCategoryVC.self)!
                                objChooseSubCategory.arrSubCategory = resObj["result"].arrayValue
                                self.navigationController?.pushViewController(objChooseSubCategory, animated: true)
                            }
                            else
                            {
                                SnackBar.show(resObj["message"].stringValue)
                            }
                            
                        }
                        else
                        {
                            SnackBar.show(resObj["message"].stringValue)
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
