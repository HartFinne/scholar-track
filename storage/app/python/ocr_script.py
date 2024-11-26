from PIL import Image, ImageOps, ImageEnhance
import sys
import os

def split_and_enlarge(image_path, enlargement_factor=2, output_dir="."):
    """
    Splits an image into two halves, enlarges them, and saves the results.
    Returns paths to the saved halves.
    """
    image = Image.open(image_path)
    width, height = image.size
    mid_x = width // 2

    # Split into two halves
    left_half = image.crop((0, 0, mid_x, height))
    right_half = image.crop((mid_x, 0, width, height))

    # Enlarge both halves
    left_enlarged = left_half.resize((mid_x * enlargement_factor, height * enlargement_factor))
    right_enlarged = right_half.resize((mid_x * enlargement_factor, height * enlargement_factor))

    # Save the halves
    base_name, ext = os.path.splitext(os.path.basename(image_path))
    left_path = os.path.join(output_dir, f"{base_name}_left{ext}")
    right_path = os.path.join(output_dir, f"{base_name}_right{ext}")
    left_enlarged.save(left_path)
    right_enlarged.save(right_path)

    return left_path, right_path

def process_image_for_ocr(image_path):
    """
    Applies preprocessing to an image for better OCR results using Pillow.
    """
    # Open the image
    image = Image.open(image_path).convert("L")  # Convert to grayscale

    # Increase contrast
    enhancer = ImageEnhance.Contrast(image)
    image = enhancer.enhance(2.0)

    # Binarize the image
    threshold = 128
    image = image.point(lambda p: 255 if p > threshold else 0)

    # Save the processed image
    base_name, ext = os.path.splitext(image_path)
    processed_path = f"{base_name}_processed{ext}"
    image.save(processed_path)

    return processed_path

def main():
    """
    Main script for preprocessing an image, splitting it, and saving processed halves.
    """
    if len(sys.argv) < 2:
        print("Usage: python ocr_script.py <image_path>")
        sys.exit(1)

    image_path = sys.argv[1]

    # Define output directory (same as input image's directory)
    output_dir = os.path.dirname(image_path)

    # Split and enlarge the image
    left_path, right_path = split_and_enlarge(image_path, enlargement_factor=2, output_dir=output_dir)

    # Process the left and right halves for OCR
    left_processed_path = process_image_for_ocr(left_path)
    right_processed_path = process_image_for_ocr(right_path)

    # Output paths to processed images
    print(left_processed_path)
    print(right_processed_path)

if __name__ == "__main__":
    main()

